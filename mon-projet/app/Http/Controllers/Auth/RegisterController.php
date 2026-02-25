<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PendingUser;
use App\Jobs\NotifyAdminsNewRegistration;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/login';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255',
                // ✅ Utilisation de notre nouvelle règle personnalisée
                'unique_email_pending'
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:etudiant,enseignant'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date'],
            'lieu_naissance' => ['nullable', 'string', 'max:100'],
            'sexe' => ['nullable', 'in:M,F'],
        ], [
            // Messages d'erreur personnalisés en français
            'email.unique_email_pending' => 'Cet email est déjà utilisé par un compte existant ou une demande est déjà en cours de traitement.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'role.required' => 'Veuillez sélectionner votre rôle.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
        ]);
    }

    protected function create(array $data)
    {
        try {
            // 1. Créer la demande en attente
            $pendingUser = PendingUser::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'telephone' => $data['telephone'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'lieu_naissance' => $data['lieu_naissance'] ?? null,
                'sexe' => $data['sexe'] ?? null,
                'status' => 'en_attente'
            ]);
            
            // 2. Générer le matricule (méthode corrigée dans PendingUser.php)
            $matricule = $pendingUser->genererMatricule();
            $pendingUser->matricule = $matricule;
            $pendingUser->save();
            
            // 3. Journaliser l'inscription
            Log::info('Nouvelle demande d\'inscription créée', [
                'pending_user_id' => $pendingUser->id,
                'email' => $pendingUser->email,
                'role' => $pendingUser->role
            ]);
            
            // 4. Notifier les admins (asynchrone si possible)
            try {
                if (config('queue.default') !== 'sync') {
                    // Utilisation d'un job asynchrone (recommandé)
                    dispatch(new NotifyAdminsNewRegistration($pendingUser));
                } else {
                    // Notification synchrone avec chunk pour éviter les timeout
                    $this->notifierAdmins($pendingUser);
                }
            } catch (\Exception $e) {
                // On ne bloque pas l'inscription si la notification échoue
                Log::warning('Échec de notification des admins: ' . $e->getMessage());
            }
            
            return redirect()->route('login')->with('success', 
                'Votre demande d\'inscription a été envoyée avec succès. Elle sera traitée par l\'administration dans les plus brefs délais.');
                
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'inscription: ' . $e->getMessage(), [
                'email' => $data['email'] ?? 'inconnu',
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur technique est survenue. Veuillez réessayer plus tard.']);
        }
    }

    /**
     * Notifier les admins de façon optimisée
     * Utilise chunk pour éviter de charger trop de données en mémoire
     */
    private function notifierAdmins(PendingUser $pendingUser): void
    {
        try {
            User::where('role', 'admin')
                ->chunk(50, function ($admins) use ($pendingUser) {
                    foreach ($admins as $admin) {
                        try {
                            $admin->notify(new \App\Notifications\NouvelleDemandeInscription($pendingUser));
                        } catch (\Exception $e) {
                            Log::warning('Erreur notification admin ' . $admin->id . ': ' . $e->getMessage());
                            continue; // Passer à l'admin suivant en cas d'erreur
                        }
                    }
                });
                
            Log::info('Notifications envoyées aux admins', [
                'pending_user_id' => $pendingUser->id
            ]);
                
        } catch (\Exception $e) {
            Log::warning('Erreur lors de la notification des admins: ' . $e->getMessage());
        }
    }

    public function register(Request $request)
    {
        // Valider les données
        $this->validator($request->all())->validate();

        // Créer la demande
        return $this->create($request->all());
    }
}
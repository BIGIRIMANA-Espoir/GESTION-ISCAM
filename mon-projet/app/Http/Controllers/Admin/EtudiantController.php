<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EtudiantService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class EtudiantController extends Controller
{
    protected EtudiantService $etudiantService;

    public function __construct(EtudiantService $etudiantService)
    {
        $this->etudiantService = $etudiantService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        if ($search) {
            $etudiants = $this->etudiantService->search($search);
            $totalEtudiants = $etudiants->count();
        } else {
            $etudiants = $this->etudiantService->getPaginated(15);
            $totalEtudiants = $etudiants->total();
        }
        
        $inscriptionsAnnee = 0;
        $moyenneGenerale = 'N/A';
        $nouveauxEtudiants = 0;
            
        return view('admin.etudiants.index', compact(
            'etudiants', 
            'search', 
            'totalEtudiants',
            'inscriptionsAnnee',
            'moyenneGenerale',
            'nouveauxEtudiants'
        ));
    }

    /**
     * Affiche le formulaire de création d'un étudiant
     */
    public function create(): View
    {
        // Récupérer tous les départements pour le menu déroulant
        $departements = \App\Models\Departement::all()->pluck('nom_departement', 'id');
        
        return view('admin.etudiants.create', compact('departements'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            // 1. Créer l'étudiant
            $etudiant = $this->etudiantService->createStudent($request->all());
            
            // 2. Si la case "Créer un compte" est cochée, créer l'utilisateur associé
            if ($request->has('creer_compte') && $request->creer_compte) {
                
                // Vérifier si l'email existe déjà dans users
                if (User::where('email', $request->email)->exists()) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Cet email est déjà utilisé par un autre utilisateur');
                }
                
                // Créer l'utilisateur
                $user = User::create([
                    'name' => $request->prenom . ' ' . $request->nom,
                    'email' => $request->email,
                    'password' => Hash::make($request->password ?? 'password123'),
                    'role' => 'etudiant'
                ]);
                
                // Lier l'étudiant à l'utilisateur
                $etudiant->user_id = $user->id;
                $etudiant->save();
            }
            
            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant créé avec succès' . 
                    ($request->has('creer_compte') ? ' et compte utilisateur créé.' : ''));
                    
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $etudiant = $this->etudiantService->getWithInscriptions($id);
        
        if (!$etudiant) {
            return redirect()->route('admin.etudiants.index')
                ->with('error', 'Étudiant non trouvé');
        }
        
        $statistiques = $this->etudiantService->getStatistics($id);
        return view('admin.etudiants.show', compact('etudiant', 'statistiques'));
    }

    public function edit(int $id)
    {
        $etudiant = $this->etudiantService->getById($id);
        
        if (!$etudiant) {
            return redirect()->route('admin.etudiants.index')
                ->with('error', 'Étudiant non trouvé');
        }
        
        // Récupérer tous les départements pour le menu déroulant
        $departements = \App\Models\Departement::all()->pluck('nom_departement', 'id');
        
        return view('admin.etudiants.edit', compact('etudiant', 'departements'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $this->etudiantService->update($id, $request->all());
            
            // Si l'étudiant a un user associé et que l'email change
            $etudiant = $this->etudiantService->getById($id);
            if ($etudiant && $etudiant->user_id) {
                $user = User::find($etudiant->user_id);
                if ($user) {
                    $user->email = $request->email;
                    $user->name = $request->prenom . ' ' . $request->nom;
                    $user->save();
                }
            }
            
            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant modifié avec succès');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $etudiant = $this->etudiantService->getById($id);
            
            // Supprimer l'utilisateur associé s'il existe
            if ($etudiant && $etudiant->user_id) {
                User::where('id', $etudiant->user_id)->delete();
            }
            
            $this->etudiantService->delete($id);
            
            return redirect()->route('admin.etudiants.index')
                ->with('success', 'Étudiant et compte utilisateur supprimés avec succès');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
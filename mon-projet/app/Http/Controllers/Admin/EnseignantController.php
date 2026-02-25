<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EnseignantService;
use App\Services\DepartementService;
use App\Models\User;
use App\Models\Departement;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class EnseignantController extends Controller
{
    protected EnseignantService $enseignantService;
    protected DepartementService $departementService;

    public function __construct(
        EnseignantService $enseignantService,
        DepartementService $departementService
    ) {
        $this->enseignantService = $enseignantService;
        $this->departementService = $departementService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        if ($search) {
            $enseignants = $this->enseignantService->search($search);
            $totalEnseignants = $enseignants->count();
        } else {
            $enseignants = $this->enseignantService->getPaginated(15);
            $totalEnseignants = $enseignants->total();
        }
        
        $totalCours = 0;
        $moyenneGlobale = 'N/A';
        $totalDepartements = $this->departementService->getAll()->count();
            
        return view('admin.enseignants.index', compact(
            'enseignants', 
            'search', 
            'totalEnseignants', 
            'totalCours', 
            'moyenneGlobale', 
            'totalDepartements'
        ));
    }

    public function create(): View
    {
        $departements = Departement::all()->pluck('nom_departement', 'id');
        return view('admin.enseignants.create', compact('departements'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            // 1. Créer l'enseignant
            $enseignant = $this->enseignantService->create($request->all());
            
            // 2. CRÉER L'UTILISATEUR SI LA CASE EST COCHÉE
            if ($request->has('creer_compte') && $request->creer_compte) {
                
                // Vérifier si l'email existe déjà dans users
                if (User::where('email', $request->email)->exists()) {
                    return redirect()->back()->withInput()
                        ->with('error', 'Cet email est déjà utilisé par un autre utilisateur');
                }
                
                // Créer l'utilisateur avec le rôle 'enseignant'
                $user = User::create([
                    'name' => $request->prenom . ' ' . $request->nom,
                    'email' => $request->email,
                    'password' => Hash::make($request->password ?? '12345678'),
                    'role' => 'enseignant'  // ✅ FORCÉ À 'enseignant'
                ]);
                
                // Lier l'enseignant à l'utilisateur
                $enseignant->user_id = $user->id;
                $enseignant->save();
            }
            
            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant créé avec succès' . 
                    ($request->has('creer_compte') ? ' et compte utilisateur créé.' : ''));
                    
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $enseignant = $this->enseignantService->getWithCourses($id);
        
        if (!$enseignant) {
            return redirect()->route('admin.enseignants.index')
                ->with('error', 'Enseignant non trouvé');
        }
        
        return view('admin.enseignants.show', compact('enseignant'));
    }

    public function edit(int $id): View
    {
        $enseignant = $this->enseignantService->getById($id);
        
        if (!$enseignant) {
            abort(404, 'Enseignant non trouvé');
        }
        
        // ✅ FORMATER CORRECTEMENT LES DÉPARTEMENTS
        $departements = Departement::all()->pluck('nom_departement', 'id');
        
        return view('admin.enseignants.edit', compact('enseignant', 'departements'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $this->enseignantService->update($id, $request->all());
            
            // Si l'enseignant a un user associé, mettre à jour ses infos
            $enseignant = $this->enseignantService->getById($id);
            if ($enseignant && $enseignant->user_id) {
                $user = User::find($enseignant->user_id);
                if ($user) {
                    $user->email = $request->email;
                    $user->name = $request->prenom . ' ' . $request->nom;
                    $user->save();
                }
            }
            
            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant modifié avec succès');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $enseignant = $this->enseignantService->getById($id);
            
            // Supprimer aussi l'utilisateur associé
            if ($enseignant && $enseignant->user_id) {
                User::where('id', $enseignant->user_id)->delete();
            }
            
            $this->enseignantService->delete($id);
            
            return redirect()->route('admin.enseignants.index')
                ->with('success', 'Enseignant et compte utilisateur supprimés avec succès');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
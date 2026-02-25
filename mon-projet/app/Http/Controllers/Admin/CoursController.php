<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CoursService;
use App\Services\DepartementService;
use App\Services\EnseignantService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CoursController extends Controller
{
    protected CoursService $coursService;
    protected DepartementService $departementService;
    protected EnseignantService $enseignantService;

    public function __construct(
        CoursService $coursService,
        DepartementService $departementService,
        EnseignantService $enseignantService
    ) {
        $this->coursService = $coursService;
        $this->departementService = $departementService;
        $this->enseignantService = $enseignantService;
    }

    /**
     * Display a listing of courses
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        if ($search) {
            $cours = $this->coursService->search($search);
        } else {
            $cours = $this->coursService->getPaginated(15);
        }
        
        return view('admin.cours.index', compact('cours', 'search'));
    }

    /**
     * Show form for creating a new course
     * CORRIGÉ : Formatage des données pour les selecteurs
     */
    public function create(): View
    {
        // Récupérer les départements avec leurs noms
        $departements = $this->departementService->getAll()->pluck('nom_departement', 'id');
        
        // Récupérer les enseignants avec leurs noms complets
        $enseignants = $this->enseignantService->getAll()->mapWithKeys(function ($enseignant) {
            return [$enseignant->id => $enseignant->prenom . ' ' . $enseignant->nom];
        });
        
        return view('admin.cours.create', compact('departements', 'enseignants'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->coursService->createCourse($request->all());
            
            return redirect()
                ->route('admin.cours.index')
                ->with('success', 'Cours créé avec succès');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified course
     */
    public function show(int $id)
    {
        $cours = $this->coursService->getWithRelations($id);
        
        if (!$cours) {
            return redirect()
                ->route('admin.cours.index')
                ->with('error', 'Cours non trouvé');
        }
        
        $statistiques = $this->coursService->getStatistics($id);
        
        return view('admin.cours.show', compact('cours', 'statistiques'));
    }

    /**
     * Show form for editing a course
     * CORRIGÉ : Formatage des données pour les selecteurs
     */
    public function edit(int $id)
    {
        $cours = $this->coursService->getById($id);
        
        if (!$cours) {
            return redirect()
                ->route('admin.cours.index')
                ->with('error', 'Cours non trouvé');
        }
        
        // Récupérer les départements avec leurs noms
        $departements = $this->departementService->getAll()->pluck('nom_departement', 'id');
        
        // Récupérer les enseignants avec leurs noms complets
        $enseignants = $this->enseignantService->getAll()->mapWithKeys(function ($enseignant) {
            return [$enseignant->id => $enseignant->prenom . ' ' . $enseignant->nom];
        });
        
        return view('admin.cours.edit', compact('cours', 'departements', 'enseignants'));
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $this->coursService->updateCourse($id, $request->all());
            
            return redirect()
                ->route('admin.cours.index')
                ->with('success', 'Cours modifié avec succès');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified course
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->coursService->deleteCourse($id);
            
            return redirect()
                ->route('admin.cours.index')
                ->with('success', 'Cours supprimé avec succès');
                
        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
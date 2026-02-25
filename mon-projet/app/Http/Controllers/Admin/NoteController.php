<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\NoteService;
use App\Services\EtudiantService;
use App\Services\CoursService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Etudiant;
use App\Models\Cours;
use App\Models\Inscription;
use App\Models\AnneeAcademique;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    protected NoteService $noteService;
    protected EtudiantService $etudiantService;
    protected CoursService $coursService;

    public function __construct(
        NoteService $noteService,
        EtudiantService $etudiantService,
        CoursService $coursService
    ) {
        $this->noteService = $noteService;
        $this->etudiantService = $etudiantService;
        $this->coursService = $coursService;
    }

    private function getAnneeAcademiqueEnCours(): string
    {
        try {
            $active = AnneeAcademique::getActive();
            if ($active) {
                return $active->annee;
            }
        } catch (\Exception $e) {}
        
        $mois = (int)date('m');
        $annee = (int)date('Y');
        return ($mois >= 9) ? $annee . '-' . ($annee + 1) : ($annee - 1) . '-' . $annee;
    }

    /**
     * Display a listing of notes
     */
    public function index(Request $request): View
    {
        $coursId = $request->get('cours_id');
        
        if ($coursId) {
            $notes = $this->noteService->getByCourse($coursId);
            $cours = $this->coursService->getById($coursId);
        } else {
            $notes = $this->noteService->getPaginated(15);
            $cours = null;
        }
        
        $statistiques = $coursId 
            ? $this->noteService->getCourseSuccessRate($coursId)
            : $this->noteService->getStatistiquesGenerales();
            
        $coursList = $this->coursService->getAll();
        
        return view('admin.notes.index', compact('notes', 'statistiques', 'coursList', 'cours'));
    }

    /**
     * Show form for creating a new note
     * CORRIGÉ : Utilisation de get() au lieu de pluck()
     */
    public function create(Request $request): View
    {
        $coursId = $request->get('cours_id');
        
        // ✅ Utiliser get() pour avoir des objets
        $etudiants = Etudiant::select('id', 'nom', 'prenom', 'matricule')
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();
        
        // ✅ Utiliser get() pour avoir des objets
        $coursList = Cours::select('id', 'code_cours', 'nom_cours')
            ->orderBy('code_cours')
            ->get();
        
        Log::info('Création note - Étudiants trouvés: ' . $etudiants->count());
        Log::info('Création note - Cours trouvés: ' . $coursList->count());
        
        // Récupération du cours si spécifié
        $cours = null;
        if ($coursId) {
            $cours = $this->coursService->getById($coursId);
        }
        
        $anneeEnCours = $this->getAnneeAcademiqueEnCours();
        
        // Données pour la saisie groupée
        $etudiantsInscrits = collect([]);
        $notesExistantes = collect([]);
        
        if ($coursId) {
            try {
                $notesExistantes = $this->noteService->getByCourse($coursId)
                    ->keyBy('etudiant_id');
                
                $etudiantsInscrits = Inscription::whereHas('etudiant')
                    ->with('etudiant')
                    ->get()
                    ->pluck('etudiant')
                    ->filter();
            } catch (\Exception $e) {
                Log::error('Erreur chargement inscriptions: ' . $e->getMessage());
            }
        }
        
        return view('admin.notes.create', compact(
            'etudiants', 
            'coursList', 
            'cours',
            'anneeEnCours',
            'etudiantsInscrits',
            'notesExistantes'
        ));
    }

    /**
     * Store a newly created note
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $this->noteService->enterGrade($request->all());
            return redirect()->route('admin.notes.index')
                ->with('success', 'Note enregistrée avec succès');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show form for bulk note entry
     */
    public function bulkCreate(Request $request): View
    {
        $coursId = $request->get('cours_id');
        
        if (!$coursId) {
            $coursList = $this->coursService->getAll();
            $anneeEnCours = $this->getAnneeAcademiqueEnCours();
            return view('admin.notes.select-cours', compact('coursList', 'anneeEnCours'));
        }
        
        $cours = $this->coursService->getWithRelations($coursId);
        $etudiants = $cours->inscriptions->map(function($inscription) {
            return $inscription->etudiant;
        })->filter();
        
        $anneeEnCours = $this->getAnneeAcademiqueEnCours();
        
        return view('admin.notes.bulk', compact('cours', 'etudiants', 'anneeEnCours'));
    }

    /**
     * Store bulk notes
     */
    public function bulkStore(Request $request): RedirectResponse
    {
        try {
            $notes = $request->input('notes', []);
            $coursId = $request->cours_id;
            
            foreach ($notes as $etudiantId => $note) {
                if ($note !== null && $note !== '') {
                    $this->noteService->enterGrade([
                        'etudiant_id' => $etudiantId,
                        'cours_id' => $coursId,
                        'note' => $note,
                        'session' => $request->session ?? 'normale',
                        'annee_academique' => $request->annee_academique
                    ]);
                }
            }
            
            return redirect()->route('admin.notes.index', ['cours_id' => $coursId])
                ->with('success', 'Notes enregistrées avec succès');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified note
     */
    public function show(int $id): View
    {
        $note = $this->noteService->getById($id);
        
        if (!$note) {
            abort(404, 'Note non trouvée');
        }
        
        $note->load(['etudiant', 'cours', 'cours.enseignant']);
        
        $studentNotes = $this->noteService->getByStudent($note->etudiant_id);
        
        $statsEtudiant = [
            'moyenne' => $this->noteService->getStudentAverage($note->etudiant_id),
            'total_notes' => $studentNotes ? $studentNotes->count() : 0
        ];
        
        $statsCours = $this->noteService->getCourseSuccessRate($note->cours_id);
        
        return view('admin.notes.show', compact('note', 'statsEtudiant', 'statsCours'));
    }

    /**
     * Show form for editing a note
     */
    public function edit(int $id): View
    {
        $note = $this->noteService->getById($id);
        
        if (!$note) {
            abort(404, 'Note non trouvée');
        }
        
        $note->load(['etudiant', 'cours']);
        
        // ✅ Utiliser get() pour la cohérence
        $etudiants = Etudiant::select('id', 'nom', 'prenom', 'matricule')
            ->orderBy('nom')
            ->get();
        
        $coursList = Cours::select('id', 'code_cours', 'nom_cours')
            ->orderBy('code_cours')
            ->get();
        
        $anneeEnCours = $this->getAnneeAcademiqueEnCours();
        
        return view('admin.notes.edit', compact('note', 'etudiants', 'coursList', 'anneeEnCours'));
    }

    /**
     * Update the specified note
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            $this->noteService->update($id, $request->all());
            return redirect()->route('admin.notes.index')
                ->with('success', 'Note modifiée avec succès');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified note
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->noteService->delete($id);
            return redirect()->route('admin.notes.index')
                ->with('success', 'Note supprimée avec succès');
        } catch (\InvalidArgumentException $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}
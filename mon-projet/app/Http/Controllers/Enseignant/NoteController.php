<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Services\CoursService;
use App\Services\NoteService;
use App\Services\AnneeAcademiqueService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    protected CoursService $coursService;
    protected NoteService $noteService;
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(
        CoursService $coursService,
        NoteService $noteService,
        AnneeAcademiqueService $anneeAcademiqueService
    ) {
        $this->coursService = $coursService;
        $this->noteService = $noteService;
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    /**
     * Liste des cours de l'enseignant
     */
    public function index(): View
    {
        $enseignant = Auth::user()->enseignant;
        $cours = $this->coursService->getByTeacher($enseignant->id);
        $anneeCourante = $this->anneeAcademiqueService->getAnneeCourante();

        return view('enseignant.notes.index', compact('cours', 'anneeCourante'));
    }

    /**
     * Formulaire de saisie des notes pour un cours
     */
    public function create(Request $request, int $coursId): View
    {
        $enseignant = Auth::user()->enseignant;
        $cours = $this->coursService->getById($coursId);

        if ($cours->enseignant_id !== $enseignant->id) {
            abort(403, 'Vous n\'êtes pas autorisé à saisir des notes pour ce cours');
        }

        $etudiants = $cours->inscriptions->map(function($inscription) {
            return $inscription->etudiant;
        })->filter();

        $session = $request->get('session', 'normale');
        $annee = $request->get('annee', date('Y') . '-' . (date('Y') + 1));

        return view('enseignant.notes.create', compact('cours', 'etudiants', 'session', 'annee'));
    }

    /**
     * Enregistrer les notes
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $notes = $request->input('notes', []);
            $coursId = $request->cours_id;
            
            foreach ($notes as $etudiantId => $note) {
                if ($note !== null && $note !== '') {
                    $this->noteService->enterGrade([
                        'etudiant_id' => $etudiantId,
                        'cours_id' => $coursId,
                        'note' => (float) $note,
                        'session' => $request->session ?? 'normale',
                        'annee_academique' => $request->annee_academique ?? date('Y') . '-' . (date('Y') + 1)
                    ]);
                }
            }

            return redirect()
                ->route('enseignant.notes.index')
                ->with('success', 'Notes enregistrées avec succès');

        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Voir les notes déjà saisies pour un cours
     */
    public function show(int $coursId): View
    {
        $enseignant = Auth::user()->enseignant;
        $cours = $this->coursService->getById($coursId);
        
        if ($cours->enseignant_id !== $enseignant->id) {
            abort(403);
        }

        $notes = $this->noteService->getByCourse($coursId);
        $statistiques = $this->noteService->getCourseSuccessRate($coursId);

        return view('enseignant.notes.show', compact('cours', 'notes', 'statistiques'));
    }

    /**
     * Modifier une note
     */
    public function edit(int $noteId): View
    {
        $note = $this->noteService->getById($noteId);
        $enseignant = Auth::user()->enseignant;
        
        if ($note->cours->enseignant_id !== $enseignant->id) {
            abort(403);
        }

        return view('enseignant.notes.edit', compact('note'));
    }

    /**
     * Mettre à jour une note
     */
    public function update(Request $request, int $noteId): RedirectResponse
    {
        try {
            $this->noteService->update($noteId, [
                'note' => $request->note,
                'session' => $request->session
            ]);

            return redirect()
                ->route('enseignant.notes.show', $request->cours_id)
                ->with('success', 'Note mise à jour avec succès');

        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
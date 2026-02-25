<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Services\NoteService;
use App\Services\EtudiantService;
use App\Services\AnneeAcademiqueService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NoteController extends Controller
{
    protected NoteService $noteService;
    protected EtudiantService $etudiantService;
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(
        NoteService $noteService,
        EtudiantService $etudiantService,
        AnneeAcademiqueService $anneeAcademiqueService
    ) {
        $this->noteService = $noteService;
        $this->etudiantService = $etudiantService;
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    public function index(): View
    {
        $etudiant = Auth::user()->etudiant;
        $anneeCourante = $this->anneeAcademiqueService->getAnneeCourante();
        
        $notes = $this->noteService->getByStudent($etudiant->id);
        
        $stats = [
            'moyenne_generale' => $this->noteService->getStudentAverage($etudiant->id),
            'total_notes' => $notes->count(),
            'notes_par_session' => [
                'normale' => $notes->where('session', 'normale')->count(),
                'rattrapage' => $notes->where('session', 'rattrapage')->count()
            ]
        ];

        $notesParCours = $notes->groupBy('cours.nom_cours');

        return view('etudiant.notes.index', compact('notes', 'stats', 'notesParCours', 'anneeCourante'));
    }

    public function releve(): View
    {
        $etudiant = Auth::user()->etudiant;
        $releve = $this->noteService->getStudentTranscript($etudiant->id);
        $anneeCourante = $this->anneeAcademiqueService->getAnneeCourante();
        
        return view('etudiant.notes.releve', compact('releve', 'anneeCourante'));
    }
}
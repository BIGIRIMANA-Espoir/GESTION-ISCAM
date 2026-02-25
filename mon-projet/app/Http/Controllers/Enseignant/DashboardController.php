<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Services\CoursService;
use App\Services\NoteService;
use App\Services\AnneeAcademiqueService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
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

    public function index(): View
    {
        $enseignant = Auth::user()->enseignant;

        if (!$enseignant) {
            abort(403, 'Accès non autorisé');
        }

        $cours = $this->coursService->getByTeacher($enseignant->id);
        $anneeCourante = $this->anneeAcademiqueService->getAnneeCourante();
        
        // ✅ Date dynamique en français
        $dateActuelle = now()->locale('fr')->translatedFormat('l d F Y');

        $stats = [
            'total_cours' => $cours->count(),
            'total_etudiants' => $cours->sum(function($c) {
                return $c->inscriptions->count();
            }),
            'notes_saisies' => $this->noteService->countByTeacher($enseignant->id),
            'derniers_cours' => $cours->take(5),
            'moyenne_generale' => $this->calculerMoyenneGenerale($cours)
        ];

        return view('enseignant.dashboard', compact('stats', 'cours', 'anneeCourante', 'dateActuelle'));
    }

    private function calculerMoyenneGenerale($cours)
    {
        $totalNotes = 0;
        $total = 0;
        
        foreach ($cours as $c) {
            $moyenne = $c->notes?->avg('note') ?? 0;
            if ($moyenne > 0) {
                $totalNotes += $moyenne;
                $total++;
            }
        }
        
        return $total > 0 ? round($totalNotes / $total, 2) : 0;
    }
}
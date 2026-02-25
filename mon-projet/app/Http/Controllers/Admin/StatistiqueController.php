<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EtudiantService;
use App\Services\EnseignantService;
use App\Services\CoursService;
use App\Services\InscriptionService;
use App\Services\NoteService;
use App\Services\AnneeAcademiqueService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatistiqueController extends Controller
{
    protected EtudiantService $etudiantService;
    protected EnseignantService $enseignantService;
    protected CoursService $coursService;
    protected InscriptionService $inscriptionService;
    protected NoteService $noteService;
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(
        EtudiantService $etudiantService,
        EnseignantService $enseignantService,
        CoursService $coursService,
        InscriptionService $inscriptionService,
        NoteService $noteService,
        AnneeAcademiqueService $anneeAcademiqueService
    ) {
        $this->etudiantService = $etudiantService;
        $this->enseignantService = $enseignantService;
        $this->coursService = $coursService;
        $this->inscriptionService = $inscriptionService;
        $this->noteService = $noteService;
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    public function index(): View
    {
        $anneeCourante = $this->anneeAcademiqueService->getAnneeCourante();
        
        $stats = [
            'etudiants' => [
                'total' => $this->etudiantService->getAll()->count(),
                'par_departement' => $this->etudiantService->getStatsByDepartement(),
                'evolution' => $this->etudiantService->getEvolution()
            ],
            'enseignants' => [
                'total' => $this->enseignantService->getAll()->count(),
                'par_departement' => $this->enseignantService->getStatsByDepartement()
            ],
            'cours' => [
                'total' => $this->coursService->getAll()->count(),
                'par_departement' => $this->coursService->getStatsByDepartement(),
                'moyennes' => $this->coursService->getAllWithAverages()
            ],
            'inscriptions' => [
                'total' => $this->inscriptionService->getAll()->count(),
                'en_attente' => $this->inscriptionService->getPending()->count(),
                'validees' => $this->inscriptionService->getValidees()->count(),
                'par_annee' => $this->inscriptionService->getStatsByYear()
            ],
            'notes' => $this->noteService->getStatistiquesGenerales(),
            'reussite' => $this->noteService->getTauxReussiteGlobal(),
            'annee_courante' => $anneeCourante
        ];

        return view('admin.statistiques.index', compact('stats'));
    }
}
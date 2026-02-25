<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\EtudiantService;
use App\Services\EnseignantService;
use App\Services\CoursService;
use App\Services\InscriptionService;
use App\Services\NoteService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected EtudiantService $etudiantService;
    protected EnseignantService $enseignantService;
    protected CoursService $coursService;
    protected InscriptionService $inscriptionService;
    protected NoteService $noteService;

    public function __construct(
        EtudiantService $etudiantService,
        EnseignantService $enseignantService,
        CoursService $coursService,
        InscriptionService $inscriptionService,
        NoteService $noteService
    ) {
        $this->etudiantService = $etudiantService;
        $this->enseignantService = $enseignantService;
        $this->coursService = $coursService;
        $this->inscriptionService = $inscriptionService;
        $this->noteService = $noteService;
    }

    public function index(): View
    {
        $stats = [
            'total_etudiants' => $this->etudiantService->getAll()->count(),
            'total_enseignants' => $this->enseignantService->getAll()->count(),
            'total_cours' => $this->coursService->getAll()->count(),
            'inscriptions_attente' => $this->inscriptionService->getPending()->count(),
            'moyenne_generale' => $this->noteService->getMoyenneGenerale(),
            'dernieres_inscriptions' => $this->inscriptionService->getPending()->take(5),
            'dernieres_notes' => $this->noteService->getAll()->take(5)
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
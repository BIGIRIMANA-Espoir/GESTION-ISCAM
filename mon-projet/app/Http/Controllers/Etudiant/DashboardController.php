<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Services\EtudiantService;
use App\Services\InscriptionService;
use App\Services\NoteService;
use App\Services\AnneeAcademiqueService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected EtudiantService $etudiantService;
    protected InscriptionService $inscriptionService;
    protected NoteService $noteService;
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(
        EtudiantService $etudiantService,
        InscriptionService $inscriptionService,
        NoteService $noteService,
        AnneeAcademiqueService $anneeAcademiqueService
    ) {
        $this->etudiantService = $etudiantService;
        $this->inscriptionService = $inscriptionService;
        $this->noteService = $noteService;
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    public function index(): View
    {
        $etudiant = Auth::user()->etudiant;
        
        if (!$etudiant) {
            abort(403, 'Accès non autorisé');
        }

        $inscriptions = $this->inscriptionService->getByStudent($etudiant->id);
        $notes = $this->noteService->getByStudent($etudiant->id);
        
        // ✅ Récupération de l'année académique correcte
        $anneeCourante = $this->anneeAcademiqueService->getAnneeCourante(); // "2025-2026"
        
        // ✅ Récupération de la date actuelle formatée
        $dateActuelle = now()->translatedFormat('F Y'); // "February 2026" en français
        
        // Vérifier si l'étudiant a une inscription pour l'année en cours
        $inscriptionActuelle = $inscriptions->first(function($inscription) use ($anneeCourante) {
            // Comparer avec l'année de l'inscription
            return $inscription->anneeAcademique && 
                   $inscription->anneeAcademique->annee === $anneeCourante;
        });
        
        $stats = [
            'moyenne_generale' => $this->noteService->getStudentAverage($etudiant->id),
            'total_credits' => $notes->sum('cours.credits'),
            'inscription_actuelle' => $inscriptionActuelle,
            'dernieres_notes' => $notes->take(5),
            'prochains_evenements' => $this->getProchainsEvenements(),
            'total_cours' => $notes->groupBy('cours_id')->count(),
            'total_notes' => $notes->count()
        ];

        return view('etudiant.dashboard', compact(
            'stats', 
            'inscriptions', 
            'notes', 
            'anneeCourante',
            'dateActuelle',
            'etudiant'
        ));
    }

    protected function getProchainsEvenements(): array
    {
        // Ces dates pourraient aussi être dynamiques
        return [
            ['date' => '15 Mars 2026', 'evenement' => 'Début des examens'],
            ['date' => '30 Mars 2026', 'evenement' => 'Fin des examens'],
            ['date' => '5 Avril 2026', 'evenement' => 'Publication des résultats']
        ];
    }
}
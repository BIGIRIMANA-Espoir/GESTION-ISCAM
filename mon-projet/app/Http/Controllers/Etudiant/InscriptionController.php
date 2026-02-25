<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use App\Services\InscriptionService;
use App\Services\AnneeAcademiqueService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class InscriptionController extends Controller
{
    protected InscriptionService $inscriptionService;
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(
        InscriptionService $inscriptionService,
        AnneeAcademiqueService $anneeAcademiqueService
    ) {
        $this->inscriptionService = $inscriptionService;
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    /**
     * Affiche les inscriptions de l'étudiant
     */
    public function index(): View
    {
        $etudiant = Auth::user()->etudiant;
        $inscriptions = $this->inscriptionService->getByStudent($etudiant->id);
        
        // Récupérer l'année en cours pour l'affichage du statut
        $anneeCourante = $this->anneeAcademiqueService->getCurrentYearOnly();
        $anneeCouranteObj = (object) $anneeCourante;
        
        // Vérifier si l'étudiant a une inscription pour l'année en cours
        $inscriptionActuelle = $inscriptions->first(function($inscription) use ($anneeCouranteObj) {
            return $inscription->annee_academique_id == $anneeCouranteObj->id;
        });
        
        return view('etudiant.inscriptions.index', compact('inscriptions', 'inscriptionActuelle', 'anneeCouranteObj'));
    }

    /**
     * Formulaire de nouvelle inscription
     */
    public function create(): View
    {
        $etudiant = Auth::user()->etudiant;
        
        // Récupérer uniquement l'année en cours
        $anneeCourante = $this->anneeAcademiqueService->getCurrentYearOnly();
        
        // Vérifier si déjà inscrit pour cette année
        $dejaInscrit = $this->inscriptionService->estInscrit(
            $etudiant->id, 
            $anneeCourante['id']
        );
        
        // Convertir en objet pour la vue
        $anneeCouranteObj = (object) $anneeCourante;
        
        return view('etudiant.inscriptions.create', compact('dejaInscrit', 'anneeCouranteObj'));
    }

    /**
     * Enregistrer une demande d'inscription
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $etudiant = Auth::user()->etudiant;
            
            $this->inscriptionService->requestInscription(
                $etudiant->id,
                $request->annee_academique_id
            );

            return redirect()
                ->route('etudiant.inscriptions.index')
                ->with('success', 'Demande d\'inscription envoyée avec succès');

        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Affiche le détail d'une inscription
     */
    public function show(int $id): View
    {
        $inscription = $this->inscriptionService->getById($id);
        
        // Vérifier que l'inscription appartient bien à l'étudiant connecté
        if ($inscription->etudiant_id !== Auth::user()->etudiant->id) {
            abort(403);
        }

        return view('etudiant.inscriptions.show', compact('inscription'));
    }

    /**
     * Annuler une demande d'inscription (si en attente)
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $inscription = $this->inscriptionService->getById($id);
            
            if ($inscription->statut !== 'en_attente') {
                throw new \InvalidArgumentException('Seules les inscriptions en attente peuvent être annulées');
            }

            $this->inscriptionService->delete($id);

            return redirect()
                ->route('etudiant.inscriptions.index')
                ->with('success', 'Demande d\'inscription annulée');

        } catch (\InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
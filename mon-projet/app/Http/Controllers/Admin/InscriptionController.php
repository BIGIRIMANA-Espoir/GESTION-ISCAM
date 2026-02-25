<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InscriptionService;
use App\Services\EtudiantService;
use App\Services\AnneeAcademiqueService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use InvalidArgumentException;

class InscriptionController extends Controller
{
    protected InscriptionService $inscriptionService;
    protected EtudiantService $etudiantService;
    protected AnneeAcademiqueService $anneeAcademiqueService;

    public function __construct(
        InscriptionService $inscriptionService,
        EtudiantService $etudiantService,
        AnneeAcademiqueService $anneeAcademiqueService
    ) {
        $this->inscriptionService = $inscriptionService;
        $this->etudiantService = $etudiantService;
        $this->anneeAcademiqueService = $anneeAcademiqueService;
    }

    public function index(Request $request): View
    {
        $statut = $request->get('statut', 'tous');
        
        if ($statut === 'en_attente') {
            $inscriptions = $this->inscriptionService->getPending();
        } elseif ($statut === 'validees') {
            $inscriptions = $this->inscriptionService->getValidees();
        } else {
            $inscriptions = $this->inscriptionService->getPaginated(15);
        }
        
        $stats = [
            'en_attente' => $this->inscriptionService->getPending()->count(),
            'validees' => $this->inscriptionService->getValidees()->count(),
        ];
        
        $annees = $this->anneeAcademiqueService->getAll()->pluck('annee', 'id');
        
        return view('admin.inscriptions.index', compact('inscriptions', 'stats', 'statut', 'annees'));
    }

    public function create(): View
    {
        $etudiants = $this->etudiantService->getAll();
        $annees = $this->anneeAcademiqueService->getAll();
        
        return view('admin.inscriptions.create', compact('etudiants', 'annees'));
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $this->inscriptionService->requestInscription(
                $request->etudiant_id,
                $request->annee_academique_id
            );
            
            return redirect()
                ->route('admin.inscriptions.index')
                ->with('success', 'Inscription créée avec succès');
                
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $inscription = $this->inscriptionService->getById($id);
        
        if (!$inscription) {
            return redirect()
                ->route('admin.inscriptions.index')
                ->with('error', 'Inscription non trouvée');
        }
        
        return view('admin.inscriptions.show', compact('inscription'));
    }

    public function approuver(int $id): RedirectResponse
    {
        try {
            $this->inscriptionService->validate($id);
            
            return redirect()
                ->route('admin.inscriptions.index')
                ->with('success', 'Inscription validée avec succès');
                
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, int $id): RedirectResponse
    {
        try {
            $this->inscriptionService->reject($id, $request->reason);
            
            return redirect()
                ->route('admin.inscriptions.index')
                ->with('success', 'Inscription rejetée');
                
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->inscriptionService->delete($id);
            
            return redirect()
                ->route('admin.inscriptions.index')
                ->with('success', 'Inscription supprimée avec succès');
                
        } catch (InvalidArgumentException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use App\Services\EtudiantService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EtudiantController extends Controller
{
    protected EtudiantService $etudiantService;

    public function __construct(EtudiantService $etudiantService)
    {
        $this->etudiantService = $etudiantService;
    }

    public function index(Request $request): View
    {
        $search = $request->get('search');
        
        if ($search) {
            // Recherche → retourne une Collection
            $etudiants = $this->etudiantService->search($search);
            $totalEtudiants = $etudiants->count();
            $isSearch = true;
        } else {
            // Pas de recherche → pagination
            $etudiants = $this->etudiantService->getPaginated(15);
            $totalEtudiants = $etudiants->total();
            $isSearch = false;
        }

        return view('enseignant.etudiants.index', compact('etudiants', 'search', 'isSearch'));
    }

    public function show(int $id): View
    {
        $etudiant = $this->etudiantService->getWithNotes($id);

        if (!$etudiant) {
            abort(404);
        }

        return view('enseignant.etudiants.show', compact('etudiant'));
    }
}
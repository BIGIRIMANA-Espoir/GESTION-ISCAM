<?php

namespace App\Http\Controllers\Etudiant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'étudiant
     */
    public function index(): View
    {
        $etudiant = Auth::user()->etudiant;
        
        return view('etudiant.profile', compact('etudiant'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $etudiant = Auth::user()->etudiant;
        
        $request->validate([
            'telephone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'email' => 'required|email|unique:etudiants,email,' . $etudiant->id,
        ]);
        
        $etudiant->update($request->only(['telephone', 'adresse', 'email']));
        
        return redirect()->route('etudiant.profile')
            ->with('success', 'Profil mis à jour avec succès');
    }
}
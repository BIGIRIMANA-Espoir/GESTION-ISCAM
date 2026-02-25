<?php

namespace App\Http\Controllers\Enseignant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Afficher le profil de l'enseignant
     */
    public function index(): View
    {
        $enseignant = Auth::user()->enseignant;
        
        return view('enseignant.profile', compact('enseignant'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $enseignant = Auth::user()->enseignant;
        
        $request->validate([
            'telephone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:enseignants,email,' . $enseignant->id,
        ]);
        
        $enseignant->update($request->only(['telephone', 'email']));
        
        return redirect()->route('enseignant.profile')
            ->with('success', 'Profil mis à jour avec succès');
    }
}
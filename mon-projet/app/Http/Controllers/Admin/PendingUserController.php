<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendingUser;
use App\Models\User;
use App\Models\Etudiant;
use App\Models\Enseignant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PendingUserController extends Controller
{
    public function index()
    {
        $demandes = PendingUser::where('status', 'en_attente')->get();
        return view('admin.pending-users.index', compact('demandes'));
    }

    public function show($id)
    {
        $demande = PendingUser::findOrFail($id);
        return view('admin.pending-users.show', compact('demande'));
    }

    public function approve($id)
    {
        $demande = PendingUser::findOrFail($id);
        
        // Créer l'utilisateur
        $user = User::create([
            'name' => $demande->prenom . ' ' . $demande->nom,
            'email' => $demande->email,
            'password' => $demande->password,
            'role' => $demande->role
        ]);

        // Créer l'entrée spécifique selon le rôle
        if ($demande->role === 'etudiant') {
            Etudiant::create([
                'matricule' => $demande->matricule,
                'nom' => $demande->nom,
                'prenom' => $demande->prenom,
                'email' => $demande->email,
                'telephone' => $demande->telephone,
                'adresse' => $demande->adresse,
                'date_naissance' => $demande->date_naissance,
                'lieu_naissance' => $demande->lieu_naissance,
                'sexe' => $demande->sexe,
                'user_id' => $user->id
            ]);
        } else {
            Enseignant::create([
                'matricule' => $demande->matricule,
                'nom' => $demande->nom,
                'prenom' => $demande->prenom,
                'email' => $demande->email,
                'telephone' => $demande->telephone,
                'grade' => 'À définir',
                'specialite' => 'À définir',
                'user_id' => $user->id
            ]);
        }

        // Marquer la demande comme validée
        $demande->status = 'valide';
        $demande->validated_at = now();
        $demande->save();

        return redirect()->route('admin.pending-users.index')
            ->with('success', 'Demande approuvée avec succès');
    }

    public function reject(Request $request, $id)
    {
        $demande = PendingUser::findOrFail($id);
        
        $demande->status = 'rejete';
        $demande->rejected_at = now();
        $demande->rejection_reason = $request->reason;
        $demande->save();

        return redirect()->route('admin.pending-users.index')
            ->with('success', 'Demande rejetée');
    }
}
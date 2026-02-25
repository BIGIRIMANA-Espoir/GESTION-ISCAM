<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;
use App\Models\Enseignant;
use App\Models\Cours;
use App\Models\Departement;
use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques RÉELLES depuis la base de données
        $stats = [
            'etudiants' => Etudiant::count(),
            'enseignants' => Enseignant::count(),
            'cours' => Cours::count(),
            'departements' => Departement::count()
        ];

        // Notifications récentes (les 3 dernières)
        $notifications = Notification::latest()
            ->take(3)
            ->get()
            ->map(function($notif) {
                return [
                    'message' => $notif->message,
                    'time' => $notif->created_at->diffForHumans(),
                    'type' => $notif->type
                ];
            });

        // Si pas de notifications, utiliser des exemples
        if ($notifications->isEmpty()) {
            $notifications = [
                ['message' => 'Nouvelle inscription: Jean Ndayishimiye', 'time' => 'Il y a 5 min', 'type' => 'success'],
                ['message' => 'Cours ajouté: Programmation Web', 'time' => 'Il y a 2 heures', 'type' => 'info'],
                ['message' => 'Réunion pédagogique demain à 10h', 'time' => 'Il y a 1 jour', 'type' => 'warning']
            ];
        }

        return view('ma-page', compact('stats', 'notifications'));
    }
}
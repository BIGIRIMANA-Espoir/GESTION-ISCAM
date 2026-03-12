<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable; // ✅ Pour aider Intelephense
use App\Models\User; // ✅ Import explicite

class NotificationController extends Controller
{
    /**
     * Afficher les notifications de l'utilisateur connecté
     */
    public function index()
    {
        $user = Auth::user();
        
        /** @var \App\Models\User $user */
        $notifications = $user->notifications()->paginate(15);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        
        /** @var \App\Models\User $user */
        $notification = $user->notifications()->find($id);
        
        if ($notification) {
            $notification->markAsRead();
        }
        
        return back()->with('success', 'Notification marquée comme lue');
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        /** @var \App\Models\User $user */
        $user->unreadNotifications->markAsRead();
        
        return back()->with('success', 'Toutes les notifications ont été marquées comme lues');
    }
}
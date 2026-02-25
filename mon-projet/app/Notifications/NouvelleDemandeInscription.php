<?php

namespace App\Notifications;

use App\Models\PendingUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NouvelleDemandeInscription extends Notification
{
    use Queueable;

    protected PendingUser $pendingUser;

    public function __construct(PendingUser $pendingUser)
    {
        $this->pendingUser = $pendingUser;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $role = $this->pendingUser->role == 'etudiant' ? 'Étudiant' : 'Enseignant';
        
        return (new MailMessage)
            ->subject('Nouvelle demande d\'inscription en attente')
            ->greeting('Bonjour Admin,')
            ->line('Une nouvelle demande d\'inscription est en attente de validation.')
            ->line("**{$role}** : {$this->pendingUser->prenom} {$this->pendingUser->nom}")
            ->line("**Email** : {$this->pendingUser->email}")
            ->action('Voir la demande', url('/admin/pending-users'))
            ->line('Merci de traiter cette demande rapidement.');
    }

    public function toArray($notifiable)
    {
        return [
            'pending_user_id' => $this->pendingUser->id,
            'nom' => $this->pendingUser->nom,
            'prenom' => $this->pendingUser->prenom,
            'email' => $this->pendingUser->email,
            'role' => $this->pendingUser->role,
            'message' => 'Nouvelle demande d\'inscription en attente'
        ];
    }
}
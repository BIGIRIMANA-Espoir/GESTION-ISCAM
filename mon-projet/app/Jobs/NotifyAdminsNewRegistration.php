<?php

namespace App\Jobs;

use App\Models\PendingUser;
use App\Models\User;
use App\Notifications\NouvelleDemandeInscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyAdminsNewRegistration implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PendingUser $pendingUser;

    public $timeout = 120; // Timeout de 2 minutes pour ce job

    public function __construct(PendingUser $pendingUser)
    {
        $this->pendingUser = $pendingUser;
    }

    public function handle(): void
    {
        try {
            User::where('role', 'admin')
                ->chunk(50, function ($admins) {
                    foreach ($admins as $admin) {
                        try {
                            $admin->notify(new NouvelleDemandeInscription($this->pendingUser));
                        } catch (\Exception $e) {
                            Log::error('Erreur notification admin ' . $admin->id . ': ' . $e->getMessage());
                            continue;
                        }
                    }
                });
                
            Log::info('Notifications admins envoyées pour pending_user: ' . $this->pendingUser->id);
            
        } catch (\Exception $e) {
            Log::error('Job notification échoué: ' . $e->getMessage());
            $this->fail($e);
        }
    }
    
    public function failed(\Throwable $exception): void
    {
        Log::critical('Échec définitif du job notification: ' . $exception->getMessage());
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sauvegarder les données existantes si nécessaire
        $existingData = [];
        if (Schema::hasTable('notifications')) {
            $existingData = DB::table('notifications')->get();
            Schema::dropIfExists('notifications');
        }
        
        // Créer la table avec la structure Laravel standard
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
        
        // Restaurer les données si nécessaire (en les adaptant)
        if (!empty($existingData)) {
            foreach ($existingData as $old) {
                DB::table('notifications')->insert([
                    'id' => (string) \Illuminate\Support\Str::uuid(),
                    'type' => $old->type ?? 'App\Notifications\GeneralNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id' => $old->user_id ?? $old->notifiable_id ?? 1,
                    'data' => json_encode(['message' => $old->message ?? 'Notification']),
                    'read_at' => $old->read_at ?? $old->lu ? now() : null,
                    'created_at' => $old->created_at ?? now(),
                    'updated_at' => $old->updated_at ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // Relations avec la table users (version moderne)
            $table->foreignId('user_id')           // Destinataire
                  ->constrained('users')           // Référence à users(id)
                  ->onDelete('cascade');           // Supprime si user supprimé
                  
            $table->foreignId('emetteur_id')        // Qui a créé la notification
                  ->nullable()                       // Peut être null
                  ->constrained('users')            // Référence à users(id)
                  ->onDelete('set null');            // Met à null si user supprimé
            
            // Contenu de la notification
            $table->string('type');                  // 'info', 'success', 'warning'
            $table->string('titre');
            $table->text('message');
            $table->string('lien')->nullable();      // Lien vers la ressource concernée
            
            // Statut de lecture
            $table->boolean('lu')->default(false);
            $table->timestamp('read_at')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index(['user_id', 'lu', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
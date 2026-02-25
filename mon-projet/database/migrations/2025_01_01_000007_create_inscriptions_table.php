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
        // ✅ CORRECTION : Créer la table 'inscriptions' (et PAS 'cours')
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')
                  ->constrained('etudiants')
                  ->onDelete('cascade');
            $table->foreignId('annee_academique_id')
                  ->constrained('annees_academiques')
                  ->onDelete('cascade');
            $table->date('date_inscription');
            $table->enum('statut', ['en_attente', 'validee', 'rejetee'])
                  ->default('en_attente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
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
        Schema::table('pending_users', function (Blueprint $table) {
            // Ajouter un index composite pour améliorer les performances des recherches
            // sur email et status (très utile pour les validations)
            $table->index(['email', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pending_users', function (Blueprint $table) {
            // Supprimer l'index composite
            $table->dropIndex(['email', 'status']);
        });
    }
};
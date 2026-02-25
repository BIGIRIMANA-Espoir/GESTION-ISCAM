<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PendingUser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Règle de validation personnalisée pour l'email
        // Cette règle vérifie:
        // 1. Si l'email existe dans users (compte approuvé) → refus
        // 2. Si l'email existe dans pending_users avec status 'en_attente' ou 'valide' → refus
        // 3. Si l'email existe avec status 'rejete' → autorisé (pour nouvelle demande)
        Validator::extend('unique_email_pending', function ($attribute, $value, $parameters, $validator) {
            
            // Étape 1: Vérifier dans la table users (comptes déjà approuvés)
            if (User::where('email', $value)->exists()) {
                return false; // Email déjà utilisé par un compte approuvé
            }
            
            // Étape 2: Vérifier dans pending_users pour les statuts bloquants
            if (PendingUser::where('email', $value)
                ->whereIn('status', ['en_attente', 'valide']) // Statuts qui bloquent une nouvelle demande
                ->exists()) {
                return false; // Demande déjà en cours ou déjà validée
            }
            
            // Étape 3: Dans tous les autres cas (email inexistant OU avec status 'rejete')
            // On autorise la création d'une nouvelle demande
            return true;
        });
    }
}
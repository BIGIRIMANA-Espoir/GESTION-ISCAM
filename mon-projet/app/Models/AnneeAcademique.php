<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class AnneeAcademique extends Model
{
    protected $table = 'annees_academiques';
    
    protected $fillable = [
        'annee', 
        'date_debut', 
        'date_fin', 
        'active'
    ];
    
    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'active' => 'boolean'
    ];
    
    /**
     * Récupérer l'année académique active
     * 
     * @return self|null
     */
    public static function getActive(): ?self
    {
        try {
            return self::where('active', true)->first();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération de l\'année active: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Récupérer l'année académique active ou créer par défaut
     * 
     * @return string
     */
    public static function getAnneeActive(): string
    {
        $active = self::getActive();
        
        if ($active) {
            return $active->annee;
        }
        
        // Calcul automatique si aucune année active n'est trouvée
        $mois = (int)date('m');
        $annee = (int)date('Y');
        
        if ($mois >= 9) {
            // À partir de Septembre, année suivante
            return $annee . '-' . ($annee + 1);
        } else {
            // Avant Septembre, année en cours
            return ($annee - 1) . '-' . $annee;
        }
    }
    
    /**
     * Vérifier si une année académique est valide
     * 
     * @param string $annee
     * @return bool
     */
    public static function estValide(string $annee): bool
    {
        // Format: 2025-2026
        if (!preg_match('/^\d{4}-\d{4}$/', $annee)) {
            return false;
        }
        
        $annees = explode('-', $annee);
        $anneeDebut = (int)$annees[0];
        $anneeFin = (int)$annees[1];
        
        // Vérifier que l'année de fin = année de début + 1
        if ($anneeFin !== $anneeDebut + 1) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Activer une année académique et désactiver les autres
     * 
     * @param int $id
     * @return bool
     */
    public static function activer(int $id): bool
    {
        try {
            // Désactiver toutes les années
            self::query()->update(['active' => false]);
            
            // Activer l'année spécifiée
            $annee = self::find($id);
            if ($annee) {
                $annee->active = true;
                $annee->save();
                return true;
            }
            
            return false;
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'activation de l\'année: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Relation avec les inscriptions
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'annee_academique_id');
    }
}
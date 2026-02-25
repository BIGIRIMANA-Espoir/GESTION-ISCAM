<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscription extends Model
{
    protected $table = 'inscriptions';
    
    protected $fillable = [
        'etudiant_id',
        'annee_academique_id',
        'date_inscription',
        'statut'
    ];
    
    protected $casts = [
        'date_inscription' => 'date',
    ];
    
    /**
     * Get the student that owns the inscription.
     */
    public function etudiant(): BelongsTo
    {
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }
    
    /**
     * Get the academic year that owns the inscription.
     */
    public function anneeAcademique(): BelongsTo
    {
        return $this->belongsTo(AnneeAcademique::class, 'annee_academique_id');
    }
}
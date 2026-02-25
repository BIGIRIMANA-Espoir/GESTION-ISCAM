<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// Importation explicite pour corriger l'erreur "Undefined type"
use App\Models\Etudiant;
use App\Models\Cours;

class Note extends Model
{
    protected $table = 'notes';
    
    protected $fillable = [
        'etudiant_id',
        'cours_id', 
        'note',
        'session',
        'annee_academique'
    ];
    
    /**
     * Récupère l'étudiant à qui appartient la note.
     */
    public function etudiant(): BelongsTo
    {
        // Utilisation de la classe importée
        return $this->belongsTo(Etudiant::class, 'etudiant_id');
    }
    
    /**
     * Récupère le cours auquel appartient la note.
     */
    public function cours(): BelongsTo
    {
        // Utilisation de la classe importée
        return $this->belongsTo(Cours::class, 'cours_id');
    }
}

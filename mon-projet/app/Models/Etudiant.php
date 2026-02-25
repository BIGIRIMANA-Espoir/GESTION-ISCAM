<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etudiant extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'etudiants';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricule', 
        'nom', 
        'prenom', 
        'date_naissance',
        'lieu_naissance', 
        'sexe', 
        'adresse', 
        'telephone', 
        'email', 
        'user_id'
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_naissance' => 'date',  // ← AJOUT POUR CONVERTIR EN OBJET CARBON
    ];
    
    /**
     * Get the inscriptions for the student.
     *
     * @return HasMany
     */
    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class);
    }
    
    /**
     * Get the notes for the student.
     *
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
    
    /**
     * Get the user account associated with the student.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
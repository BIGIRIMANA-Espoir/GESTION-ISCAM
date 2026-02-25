<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enseignant extends Model
{
    protected $table = 'enseignants';
    
    protected $fillable = [
        'matricule', 
        'nom', 
        'prenom', 
        'grade', 
        'specialite', 
        'email', 
        'telephone', 
        'departement_id',
        'user_id'
    ];
    
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
    
    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
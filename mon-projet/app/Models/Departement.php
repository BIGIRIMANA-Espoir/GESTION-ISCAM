<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Departement extends Model
{
    protected $table = 'departements';
    
    protected $fillable = [
        'nom_departement', 
        'faculte_id'
    ];
    
    public function faculte(): BelongsTo
    {
        return $this->belongsTo(Faculte::class);
    }
    
    public function cours(): HasMany
    {
        return $this->hasMany(Cours::class);
    }
    
    public function enseignants(): HasMany
    {
        return $this->hasMany(Enseignant::class);
    }
}
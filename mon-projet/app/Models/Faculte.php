<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculte extends Model
{
    protected $table = 'facultes';
    
    protected $fillable = [
        'nom_faculte', 
        'description'
    ];
    
    public function departements(): HasMany
    {
        return $this->hasMany(Departement::class);
    }
}
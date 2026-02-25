<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cours extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cours';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_cours',
        'nom_cours',
        'credits',
        'description',
        'departement_id',
        'enseignant_id'
    ];
    
    /**
     * Get the department that owns the course.
     *
     * @return BelongsTo
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
    
    /**
     * Get the teacher that teaches the course.
     *
     * @return BelongsTo
     */
    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }
    
    /**
     * Get the notes for the course.
     *
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class, 'cours_id');
    }
}
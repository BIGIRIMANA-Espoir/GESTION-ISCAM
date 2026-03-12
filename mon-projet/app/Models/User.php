<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    /**
     * Get the etudiant associated with the user.
     */
    public function etudiant()
    {
        return $this->hasOne(Etudiant::class, 'user_id');
    }

    /**
     * Get the enseignant associated with the user.
     */
    public function enseignant()
    {
        return $this->hasOne(Enseignant::class, 'user_id');
    }
    
    /**
     * Vérifier si l'utilisateur est un admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Vérifier si l'utilisateur est un étudiant
     */
    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }

    /**
     * Vérifier si l'utilisateur est un enseignant
     */
    public function isEnseignant(): bool
    {
        return $this->role === 'enseignant';
    }

    /**
     * Scope pour filtrer les admins uniquement
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope pour filtrer les étudiants
     */
    public function scopeEtudiants($query)
    {
        return $query->where('role', 'etudiant');
    }

    /**
     * Scope pour filtrer les enseignants
     */
    public function scopeEnseignants($query)
    {
        return $query->where('role', 'enseignant');
    }
}
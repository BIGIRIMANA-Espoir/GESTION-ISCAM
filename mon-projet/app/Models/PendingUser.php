<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log; // ✅ IMPORT AJOUTÉ

class PendingUser extends Model
{
    use HasFactory;

    protected $table = 'pending_users';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'matricule',
        'telephone',
        'adresse',
        'date_naissance',
        'lieu_naissance',
        'sexe',
        'status',
        'validated_at',
        'rejected_at',
        'rejection_reason'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'rejected_at' => 'datetime',
        'date_naissance' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function genererMatricule(): string
    {
        try {
            if ($this->role === 'etudiant') {
                $lastMatricule = self::where('role', 'etudiant')
                    ->whereNotNull('matricule')
                    ->where('matricule', 'like', '88%')
                    ->max('matricule');
                
                if ($lastMatricule) {
                    $lastNumber = intval(substr($lastMatricule, -3));
                    $numero = $lastNumber + 1;
                } else {
                    $numero = 1;
                }
                
                return '88' . str_pad($numero, 3, '0', STR_PAD_LEFT);
            } 
            elseif ($this->role === 'enseignant') {
                $lastMatricule = self::where('role', 'enseignant')
                    ->whereNotNull('matricule')
                    ->where('matricule', 'like', 'SSS%')
                    ->max('matricule');
                
                if ($lastMatricule) {
                    $lastNumber = intval(substr($lastMatricule, -3));
                    $numero = $lastNumber + 1;
                } else {
                    $numero = 1;
                }
                
                return 'SSS' . str_pad($numero, 3, '0', STR_PAD_LEFT);
            }
            
            return $this->role === 'etudiant' ? '88001' : 'SSS001';
            
        } catch (\Exception $e) {
            Log::error('Erreur génération matricule: ' . $e->getMessage()); // ✅ Log fonctionne maintenant
            return $this->role === 'etudiant' ? '88001' : 'SSS001';
        }
    }

    // ... le reste des méthodes (scopes, etc.)
}
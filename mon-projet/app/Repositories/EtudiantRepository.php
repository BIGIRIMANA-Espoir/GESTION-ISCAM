<?php

namespace App\Repositories;

use App\Models\Etudiant;
use Illuminate\Support\Collection;

class EtudiantRepository extends BaseRepository
{
    /**
     * Constructor
     */
    public function __construct(Etudiant $etudiant)
    {
        parent::__construct($etudiant);
    }

    /**
     * Find student by matricule
     *
     * @param string $matricule
     * @return Etudiant|null
     */
    public function findByMatricule(string $matricule): ?Etudiant
    {
        return $this->model->where('matricule', $matricule)->first();
    }

    /**
     * Search students by name or surname
     *
     * @param string $search
     * @return Collection
     */
    public function search(string $search): Collection
    {
        return $this->model->where('nom', 'LIKE', "%{$search}%")
            ->orWhere('prenom', 'LIKE', "%{$search}%")
            ->orWhere('matricule', 'LIKE', "%{$search}%")
            ->get();
    }

    /**
     * Get student with their inscriptions
     *
     * @param int $id
     * @return Etudiant|null
     */
    public function getWithInscriptions(int $id): ?Etudiant
    {
        return $this->model->with('inscriptions.anneeAcademique')->find($id);
    }

    /**
     * Get student with their notes
     *
     * @param int $id
     * @return Etudiant|null
     */
    public function getWithNotes(int $id): ?Etudiant
    {
        return $this->model->with('notes.cours')->find($id);
    }

    /**
     * Count students created in a specific year
     * MÉTHODE AJOUTÉE POUR L'ÉVOLUTION (Solution C)
     *
     * @param int $year
     * @return int
     */
    public function countByYear(int $year): int
    {
        return $this->model->whereYear('created_at', $year)->count();
    }
}
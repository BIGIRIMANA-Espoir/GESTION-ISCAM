<?php

namespace App\Repositories;

use App\Models\Enseignant;
use Illuminate\Support\Collection;

class EnseignantRepository extends BaseRepository
{
    /**
     * EnseignantRepository constructor.
     *
     * @param Enseignant $enseignant
     */
    public function __construct(Enseignant $enseignant)
    {
        parent::__construct($enseignant);
    }

    /**
     * Find teacher by matricule
     *
     * @param string $matricule
     * @return Enseignant|null
     */
    public function findByMatricule(string $matricule): ?Enseignant
    {
        return $this->model->where('matricule', $matricule)->first();
    }

    /**
     * Get teacher with their courses
     *
     * @param int $id
     * @return Enseignant|null
     */
    public function getWithCours(int $id): ?Enseignant
    {
        return $this->model->with('cours.departement')->find($id);
    }

    /**
     * Get teachers by department
     *
     * @param int $departementId
     * @return Collection
     */
    public function getByDepartement(int $departementId): Collection
    {
        return $this->model->where('departement_id', $departementId)->get();
    }

    /**
     * Search teachers by name, surname, matricule or email
     *
     * @param string $search
     * @return Collection
     */
    public function search(string $search): Collection
    {
        return $this->model->where('nom', 'LIKE', "%{$search}%")
            ->orWhere('prenom', 'LIKE', "%{$search}%")
            ->orWhere('matricule', 'LIKE', "%{$search}%")
            ->orWhere('email', 'LIKE', "%{$search}%")
            ->get();
    }

    /**
     * Get teachers with course count
     *
     * @return Collection
     */
    public function getWithCourseCount(): Collection
    {
        return $this->model->withCount('cours')->get();
    }

    /**
     * Find teacher by email
     *
     * @param string $email
     * @return Enseignant|null
     */
    public function findByEmail(string $email): ?Enseignant
    {
        return $this->model->where('email', $email)->first();
    }
}
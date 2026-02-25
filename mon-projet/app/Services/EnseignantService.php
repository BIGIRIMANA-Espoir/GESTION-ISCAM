<?php

namespace App\Services;

use App\Repositories\EnseignantRepository;
use App\Models\Enseignant;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;  // ← CORRIGÉ (pas Contracts)

class EnseignantService extends BaseService
{
    protected EnseignantRepository $enseignantRepository;

    /**
     * EnseignantService constructor.
     *
     * @param EnseignantRepository $enseignantRepository
     */
    public function __construct(EnseignantRepository $enseignantRepository)
    {
        parent::__construct($enseignantRepository);
        $this->enseignantRepository = $enseignantRepository;
    }

    /**
     * Get teacher by matricule
     *
     * @param string $matricule
     * @return Enseignant|null
     */
    public function getByMatricule(string $matricule): ?Enseignant
    {
        return $this->enseignantRepository->findByMatricule($matricule);
    }

    /**
     * Get teacher with courses
     *
     * @param int $id
     * @return Enseignant|null
     */
    public function getWithCourses(int $id): ?Enseignant
    {
        return $this->enseignantRepository->getWithCours($id);
    }

    /**
     * Get teachers by department
     *
     * @param int $departementId
     * @return Collection
     */
    public function getByDepartment(int $departementId): Collection
    {
        return $this->enseignantRepository->getByDepartement($departementId);
    }

    /**
     * Search teachers
     *
     * @param string $search
     * @return Collection
     */
    public function search(string $search): Collection
    {
        return $this->enseignantRepository->search($search)->load('departement');
    }

    /**
     * Get paginated teachers
     * CORRIGÉ : Utilise le bon type
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return $this->enseignantRepository->paginate($perPage);
    }

    /**
     * Get statistics by department
     *
     * @return array
     */
    public function getStatsByDepartement(): array
    {
        $enseignants = $this->enseignantRepository->all()->load('departement');
        return $enseignants->groupBy('departement.nom_departement')
            ->map(fn($group) => $group->count())
            ->toArray();
    }
}
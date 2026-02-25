<?php

namespace App\Services;

use App\Repositories\DepartementRepository;
use Illuminate\Support\Collection;

class DepartementService extends BaseService
{
    protected DepartementRepository $departementRepository;

    /**
     * DepartementService constructor.
     *
     * @param DepartementRepository $departementRepository
     */
    public function __construct(DepartementRepository $departementRepository)
    {
        parent::__construct($departementRepository);
        $this->departementRepository = $departementRepository;
    }

    /**
     * Get all departments
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->departementRepository->all();
    }

    /**
     * Get department by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->departementRepository->find($id);
    }

    /**
     * Get department with faculties
     *
     * @param int $id
     * @return mixed
     */
    public function getWithFaculte(int $id)
    {
        return $this->departementRepository->getWithFaculte($id);
    }

    /**
     * Get departments by faculty
     *
     * @param int $faculteId
     * @return Collection
     */
    public function getByFaculte(int $faculteId): Collection
    {
        return $this->departementRepository->getByFaculte($faculteId);
    }

    /**
     * Create department
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->departementRepository->create($data);
    }

    /**
     * Update department
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->departementRepository->update($id, $data);
    }

    /**
     * Delete department
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->departementRepository->delete($id);
    }
}
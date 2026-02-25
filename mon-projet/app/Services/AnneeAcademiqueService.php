<?php

namespace App\Services;

use App\Repositories\AnneeAcademiqueRepository;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AnneeAcademiqueService extends BaseService
{
    protected AnneeAcademiqueRepository $anneeAcademiqueRepository;

    /**
     * AnneeAcademiqueService constructor.
     *
     * @param AnneeAcademiqueRepository $anneeAcademiqueRepository
     */
    public function __construct(AnneeAcademiqueRepository $anneeAcademiqueRepository)
    {
        parent::__construct($anneeAcademiqueRepository);
        $this->anneeAcademiqueRepository = $anneeAcademiqueRepository;
    }

    /**
     * Get all academic years
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->anneeAcademiqueRepository->all();
    }

    /**
     * Get academic year by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->anneeAcademiqueRepository->find($id);
    }

    /**
     * Get current academic year
     *
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->anneeAcademiqueRepository->getCurrent();
    }

    /**
     * Get active academic year
     *
     * @return mixed
     */
    public function getActive()
    {
        return $this->anneeAcademiqueRepository->getActive();
    }

    /**
     * Get academic year by year string
     *
     * @param string $year
     * @return mixed
     */
    public function getByYear(string $year)
    {
        return $this->anneeAcademiqueRepository->getByYear($year);
    }

    /**
     * Create academic year
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->anneeAcademiqueRepository->create($data);
    }

    /**
     * Update academic year
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->anneeAcademiqueRepository->update($id, $data);
    }

    /**
     * Delete academic year
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->anneeAcademiqueRepository->delete($id);
    }

    /**
     * Récupère l'année académique en cours uniquement
     * Basée sur l'année réelle
     *
     * @return array
     */
    public function getCurrentYearOnly(): array
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        
        // Règle : L'année académique commence en septembre
        // Si on est entre janvier et août, l'année en cours est (année-1)-(année)
        // Si on est entre septembre et décembre, l'année en cours est (année)-(année+1)
        
        if ($currentMonth <= 8) { // Janvier à Août
            $start = $currentYear - 1;
            $end = $currentYear;
        } else { // Septembre à Décembre
            $start = $currentYear;
            $end = $currentYear + 1;
        }
        
        return [
            'id' => 1,
            'annee' => $start . '-' . $end,
            'active' => true
        ];
    }
    
    /**
     * Pour compatibilité, retourne un tableau avec une seule année
     *
     * @return array
     */
    public function getAnneesDynamiques(): array
    {
        return [$this->getCurrentYearOnly()];
    }
    
    /**
     * Retourne l'année académique en cours sous forme de chaîne
     * Exemple: "2025-2026"
     *
     * @return string
     */
    public function getAnneeCourante(): string
    {
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        
        if ($currentMonth <= 8) { // Janvier à Août
            return ($currentYear - 1) . '-' . $currentYear;
        } else { // Septembre à Décembre
            return $currentYear . '-' . ($currentYear + 1);
        }
    }
}
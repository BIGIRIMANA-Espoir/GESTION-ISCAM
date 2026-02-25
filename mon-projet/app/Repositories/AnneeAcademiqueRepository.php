<?php

namespace App\Repositories;

use App\Models\AnneeAcademique;

class AnneeAcademiqueRepository extends BaseRepository
{
    public function __construct(AnneeAcademique $anneeAcademique)
    {
        parent::__construct($anneeAcademique);
    }

    public function getActive(): ?AnneeAcademique
    {
        return $this->model->where('active', true)->first();
    }

    public function getByYear(string $year): ?AnneeAcademique
    {
        return $this->model->where('annee', $year)->first();
    }

    public function getCurrent(): ?AnneeAcademique
    {
        $currentYear = date('Y') . '-' . (date('Y') + 1);
        return $this->getByYear($currentYear) ?? $this->getActive();
    }

    public function getForSelect(): array
    {
        return $this->model->orderBy('annee', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                $suffixe = $item->active ? ' (Active)' : '';
                return [$item->id => $item->annee . $suffixe];
            })
            ->toArray();
    }
}
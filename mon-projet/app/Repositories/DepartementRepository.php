<?php

namespace App\Repositories;

use App\Models\Departement;
use Illuminate\Support\Collection;

class DepartementRepository extends BaseRepository
{
    public function __construct(Departement $departement)
    {
        parent::__construct($departement);
    }

    public function getWithFaculte(int $id)
    {
        return $this->model->with('faculte')->find($id);
    }

    public function getByFaculte(int $faculteId): Collection
    {
        return $this->model->where('faculte_id', $faculteId)->get();
    }
}
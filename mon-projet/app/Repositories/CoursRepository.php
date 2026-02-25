<?php

namespace App\Repositories;

use App\Models\Cours;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CoursRepository extends BaseRepository
{
    public function __construct(Cours $cours)
    {
        parent::__construct($cours);
    }

    /**
     * Get all courses
     * Surcharge de la méthode all() du BaseRepository
     */
    public function all(): Collection
    {
        Log::info('📁 CoursRepository::all() appelé');
        
        try {
            $cours = $this->model->all();
            Log::info('✅ CoursRepository: ' . $cours->count() . ' cours trouvés');
            return $cours;
        } catch (\Exception $e) {
            Log::error('❌ Erreur CoursRepository::all(): ' . $e->getMessage());
            return collect([]);
        }
    }

    public function findByCode(string $code): ?Cours
    {
        Log::info('🔍 Recherche cours par code: ' . $code);
        return $this->model->where('code_cours', $code)->first();
    }

    public function getWithRelations(int $id): ?Cours
    {
        Log::info('🔍 Chargement cours ID ' . $id . ' avec relations');
        return $this->model->with(['departement', 'enseignant'])->find($id);
    }

    public function getByDepartement(int $departementId): Collection
    {
        Log::info('🔍 Cours du département: ' . $departementId);
        return $this->model->where('departement_id', $departementId)->get();
    }

    public function getByEnseignant(int $enseignantId): Collection
    {
        Log::info('🔍 Cours de l\'enseignant: ' . $enseignantId);
        return $this->model->where('enseignant_id', $enseignantId)->get();
    }
}
<?php

namespace App\Services;

use App\Repositories\CoursRepository;
use App\Models\Cours;
use App\Models\Note;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CoursService extends BaseService
{
    protected CoursRepository $coursRepository;

    public function __construct(CoursRepository $coursRepository)
    {
        parent::__construct($coursRepository);
        $this->coursRepository = $coursRepository;
    }

    /**
     * Get all courses
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        Log::info('🔍 CoursService::getAll() appelé');
        
        try {
            $cours = $this->coursRepository->all();
            Log::info('✅ Cours trouvés dans repository: ' . $cours->count());
            
            if ($cours->isEmpty()) {
                Log::warning('⚠️ Aucun cours trouvé dans la base de données');
            } else {
                // Log du premier cours pour vérification
                $premier = $cours->first();
                Log::info('📚 Premier cours - ID: ' . ($premier->id ?? 'N/A') . 
                         ', Code: ' . ($premier->code_cours ?? 'N/A') . 
                         ', Nom: ' . ($premier->nom_cours ?? 'N/A'));
            }
            
            return $cours;
        } catch (\Exception $e) {
            Log::error('❌ Erreur dans CoursService::getAll(): ' . $e->getMessage());
            return collect([]);
        }
    }

    /**
     * Get course by code
     *
     * @param string $code
     * @return Cours|null
     */
    public function getByCode(string $code): ?Cours
    {
        Log::info('🔍 Recherche cours par code: ' . $code);
        return $this->coursRepository->findByCode($code);
    }

    /**
     * Get course with relations
     *
     * @param int $id
     * @return Cours|null
     */
    public function getWithRelations(int $id): ?Cours
    {
        Log::info('🔍 Chargement cours ID ' . $id . ' avec relations');
        return $this->coursRepository->getWithRelations($id);
    }

    /**
     * Get courses by department
     *
     * @param int $departementId
     * @return Collection
     */
    public function getByDepartment(int $departementId): Collection
    {
        Log::info('🔍 Cours par département: ' . $departementId);
        return $this->coursRepository->getByDepartement($departementId);
    }

    /**
     * Get courses by teacher
     *
     * @param int $enseignantId
     * @return Collection
     */
    public function getByTeacher(int $enseignantId): Collection
    {
        Log::info('🔍 Cours par enseignant: ' . $enseignantId);
        return $this->coursRepository->getByEnseignant($enseignantId);
    }

    /**
     * Get course statistics
     *
     * @param int $id
     * @return array
     */
    public function getStatistics(int $id): array
    {
        Log::info('📊 Statistiques pour cours ID: ' . $id);
        
        $cours = $this->getWithRelations($id);
        
        if (!$cours) {
            Log::warning('⚠️ Cours non trouvé pour les statistiques');
            return [];
        }

        $notes = $cours->notes;
        
        $stats = [
            'total_etudiants' => $notes->count(),
            'moyenne_classe' => $notes->avg('note') ?? 0,
            'note_min' => $notes->min('note'),
            'note_max' => $notes->max('note'),
            'reussite' => $notes->where('note', '>=', 10)->count(),
            'echec' => $notes->where('note', '<', 10)->count(),
            'taux_reussite' => $notes->count() > 0 
                ? round(($notes->where('note', '>=', 10)->count() / $notes->count()) * 100, 2)
                : 0
        ];
        
        Log::info('✅ Statistiques calculées: ' . json_encode($stats));
        return $stats;
    }

    /**
     * Get all courses with their averages
     *
     * @return Collection
     */
    public function getAllWithAverages(): Collection
    {
        Log::info('🔍 Récupération de tous les cours avec moyennes');
        
        $cours = $this->coursRepository->all()->map(function($cours) {
            $cours->moyenne = $cours->notes->avg('note');
            return $cours;
        });
        
        Log::info('✅ ' . $cours->count() . ' cours avec moyennes chargés');
        return $cours;
    }

    /**
     * Get course statistics by department
     *
     * @return array
     */
    public function getStatsByDepartement(): array
    {
        Log::info('🔍 Statistiques par département');
        
        $cours = $this->coursRepository->all();
        $stats = $cours->groupBy('departement.nom_departement')
            ->map(fn($groupe) => $groupe->count())
            ->toArray();
            
        Log::info('✅ Statistiques par département: ' . json_encode($stats));
        return $stats;
    }

    /**
     * Create course with validation
     *
     * @param array $data
     * @return Cours
     * @throws \InvalidArgumentException
     */
    public function createCourse(array $data): Cours
    {
        Log::info('➕ Création d\'un nouveau cours: ' . ($data['code_cours'] ?? 'N/A'));
        
        $this->validateCourseData($data);
        
        if ($this->getByCode($data['code_cours'])) {
            Log::error('❌ Code cours déjà existant: ' . $data['code_cours']);
            throw new \InvalidArgumentException("Course code already exists");
        }
        
        $cours = $this->create($data);
        Log::info('✅ Cours créé avec ID: ' . $cours->id);
        
        return $cours;
    }

    /**
     * Validate course data
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    protected function validateCourseData(array $data): void
    {
        if (empty($data['code_cours'])) {
            throw new \InvalidArgumentException("Course code is required");
        }
        
        if (empty($data['nom_cours'])) {
            throw new \InvalidArgumentException("Course name is required");
        }
        
        if (empty($data['credits']) || $data['credits'] < 1 || $data['credits'] > 30) {
            throw new \InvalidArgumentException("Credits must be between 1 and 30");
        }
        
        if (empty($data['departement_id'])) {
            throw new \InvalidArgumentException("Department is required");
        }
        
        if (empty($data['enseignant_id'])) {
            throw new \InvalidArgumentException("Teacher is required");
        }
    }
}
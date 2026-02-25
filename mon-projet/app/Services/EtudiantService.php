<?php

namespace App\Services;

use App\Repositories\EtudiantRepository;
use App\Models\Etudiant;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class EtudiantService extends BaseService
{
    protected EtudiantRepository $etudiantRepository;

    public function __construct(EtudiantRepository $etudiantRepository)
    {
        parent::__construct($etudiantRepository);
        $this->etudiantRepository = $etudiantRepository;
    }

    /**
     * Récupérer un étudiant par son matricule
     *
     * @param string $matricule
     * @return Etudiant|null
     */
    public function getByMatricule(string $matricule): ?Etudiant
    {
        return $this->etudiantRepository->findByMatricule($matricule);
    }

    /**
     * Rechercher des étudiants
     *
     * @param string $search
     * @return Collection
     */
    public function search(string $search): Collection
    {
        return $this->etudiantRepository->search($search);
    }

    /**
     * Récupérer un étudiant avec ses inscriptions
     *
     * @param int $id
     * @return Etudiant|null
     */
    public function getWithInscriptions(int $id): ?Etudiant
    {
        return $this->etudiantRepository->getWithInscriptions($id);
    }

    /**
     * Récupérer un étudiant avec ses notes
     *
     * @param int $id
     * @return Etudiant|null
     */
    public function getWithNotes(int $id): ?Etudiant
    {
        return $this->etudiantRepository->getWithNotes($id);
    }

    /**
     * Obtenir les statistiques d'un étudiant
     *
     * @param int $id
     * @return array
     */
    public function getStatistics(int $id): array
    {
        $etudiant = $this->getWithNotes($id);
        
        if (!$etudiant) {
            return [];
        }

        $notes = $etudiant->notes;
        
        return [
            'total_notes' => $notes->count(),
            'moyenne_generale' => $notes->avg('note') ?? 0,
            'notes_par_cours' => $notes->groupBy('cours.nom_cours')
                ->map(fn($n) => $n->avg('note')),
            'reussite' => $notes->where('note', '>=', 10)->count(),
            'echec' => $notes->where('note', '<', 10)->count()
        ];
    }

    /**
     * Obtenir les statistiques des étudiants par département
     *
     * @return array
     */
    public function getStatsByDepartement(): array
    {
        $etudiants = $this->etudiantRepository->all();
        return $etudiants->groupBy('departement.nom_departement')
            ->map(fn($groupe) => $groupe->count())
            ->toArray();
    }

    /**
     * Obtenir l'évolution des étudiants par année
     *
     * @return array
     */
    public function getEvolution(): array
    {
        $years = [date('Y')-2, date('Y')-1, date('Y')];
        $data = [];
        
        foreach ($years as $year) {
            $data[] = $this->etudiantRepository->countByYear($year);
        }
        
        return [
            'labels' => $years,
            'data' => $data
        ];
    }

    /**
     * Créer un étudiant avec validation
     *
     * @param array $data
     * @return Etudiant
     * @throws \InvalidArgumentException
     */
    public function createStudent(array $data): Etudiant
    {
        $this->validateStudentData($data);
        
        // Vérifier si le matricule existe déjà
        if ($this->getByMatricule($data['matricule'])) {
            throw new \InvalidArgumentException("Ce matricule existe déjà");
        }
        
        return $this->create($data);
    }

    /**
     * Valider les données de l'étudiant
     *
     * @param array $data
     * @throws \InvalidArgumentException
     */
    protected function validateStudentData(array $data): void
    {
        if (empty($data['matricule'])) {
            throw new \InvalidArgumentException("Le matricule est requis");
        }
        
        if (empty($data['nom'])) {
            throw new \InvalidArgumentException("Le nom est requis");
        }
        
        if (empty($data['prenom'])) {
            throw new \InvalidArgumentException("Le prénom est requis");
        }
        
        if (empty($data['email'])) {
            throw new \InvalidArgumentException("L'email est requis");
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException("Le format de l'email est invalide");
        }
    }
}
<?php

namespace App\Services;

use App\Repositories\InscriptionRepository;
use App\Repositories\EtudiantRepository;
use App\Repositories\AnneeAcademiqueRepository;
use App\Models\Inscription;
use App\Models\Notification;
use Illuminate\Support\Collection;

class InscriptionService extends BaseService
{
    protected InscriptionRepository $inscriptionRepository;
    protected EtudiantRepository $etudiantRepository;
    protected AnneeAcademiqueRepository $anneeAcademiqueRepository;

    public function __construct(
        InscriptionRepository $inscriptionRepository,
        EtudiantRepository $etudiantRepository,
        AnneeAcademiqueRepository $anneeAcademiqueRepository
    ) {
        parent::__construct($inscriptionRepository);
        $this->inscriptionRepository = $inscriptionRepository;
        $this->etudiantRepository = $etudiantRepository;
        $this->anneeAcademiqueRepository = $anneeAcademiqueRepository;
    }

    public function getByStudent(int $etudiantId): Collection
    {
        return $this->inscriptionRepository->getByEtudiant($etudiantId);
    }

    public function getByAcademicYear(int $anneeId): Collection
    {
        return $this->inscriptionRepository->getByAnnee($anneeId);
    }

    public function getPending(): Collection
    {
        return $this->inscriptionRepository->getEnAttente();
    }

    public function getValidees(): Collection
    {
        return $this->inscriptionRepository->getValidees();
    }

    public function validate(int $id): bool
    {
        $result = $this->inscriptionRepository->valider($id);
        
        if ($result) {
            $inscription = $this->getById($id);
            if ($inscription && $inscription->etudiant && $inscription->etudiant->user_id) {
                Notification::create([
                    'user_id' => $inscription->etudiant->user_id,
                    'type' => 'success',
                    'titre' => 'Inscription validée',
                    'message' => 'Votre inscription a été validée avec succès',
                    'lien' => '/etudiant/inscriptions',
                    'lu' => false
                ]);
            }
        }
        
        return $result;
    }

    public function reject(int $id, string $reason = ''): bool
    {
        $result = $this->inscriptionRepository->rejeter($id);
        
        if ($result) {
            $inscription = $this->getById($id);
            if ($inscription && $inscription->etudiant && $inscription->etudiant->user_id) {
                Notification::create([
                    'user_id' => $inscription->etudiant->user_id,
                    'type' => 'warning',
                    'titre' => 'Inscription rejetée',
                    'message' => $reason ?: 'Votre inscription a été rejetée',
                    'lien' => '/etudiant/inscriptions',
                    'lu' => false
                ]);
            }
        }
        
        return $result;
    }

    public function requestInscription(int $etudiantId, int $anneeId): Inscription
    {
        $etudiant = $this->etudiantRepository->find($etudiantId);
        $annee = $this->anneeAcademiqueRepository->find($anneeId);
        
        if (!$etudiant) {
            throw new \InvalidArgumentException("Étudiant non trouvé");
        }
        
        if (!$annee) {
            throw new \InvalidArgumentException("Année académique non trouvée");
        }
        
        // Vérifier si l'étudiant est déjà inscrit pour cette année
        if ($this->inscriptionRepository->estInscrit($etudiantId, $anneeId)) {
            throw new \InvalidArgumentException("Cet étudiant est déjà inscrit pour cette année");
        }
        
        return $this->create([
            'etudiant_id' => $etudiantId,
            'annee_academique_id' => $anneeId,
            'date_inscription' => now(),
            'statut' => 'en_attente'
        ]);
    }

    public function getStatisticsByYear(int $anneeId): array
    {
        return $this->inscriptionRepository->getStatistiquesByAnnee($anneeId);
    }

    /**
     * Get inscription statistics grouped by year
     */
    public function getStatsByYear(): array
    {
        $inscriptions = $this->inscriptionRepository->all();
        
        return $inscriptions->groupBy(function($item) {
            return $item->anneeAcademique->annee ?? 'Inconnue';
        })->map(function($groupe) {
            return [
                'total' => $groupe->count(),
                'en_attente' => $groupe->where('statut', 'en_attente')->count(),
                'validees' => $groupe->where('statut', 'validee')->count(),
                'rejetees' => $groupe->where('statut', 'rejetee')->count()
            ];
        })->toArray();
    }

    /**
     * ✅ NOUVELLE MÉTHODE : Vérifier si un étudiant est déjà inscrit pour une année
     *
     * @param int $etudiantId
     * @param int $anneeId
     * @return bool
     */
    public function estInscrit(int $etudiantId, int $anneeId): bool
    {
        return $this->inscriptionRepository->estInscrit($etudiantId, $anneeId);
    }
}
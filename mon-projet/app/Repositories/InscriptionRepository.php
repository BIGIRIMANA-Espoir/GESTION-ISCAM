<?php

namespace App\Repositories;

use App\Models\Inscription;
use Illuminate\Support\Collection;

class InscriptionRepository extends BaseRepository
{
    public function __construct(Inscription $inscription)
    {
        parent::__construct($inscription);
    }

    public function getByEtudiant(int $etudiantId): Collection
    {
        return $this->model->where('etudiant_id', $etudiantId)
            ->with('anneeAcademique')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getByAnnee(int $anneeId): Collection
    {
        return $this->model->where('annee_academique_id', $anneeId)
            ->with('etudiant')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getEnAttente(): Collection
    {
        return $this->model->where('statut', 'en_attente')
            ->with(['etudiant', 'anneeAcademique'])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function getValidees(): Collection
    {
        return $this->model->where('statut', 'validee')
            ->with(['etudiant', 'anneeAcademique'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function valider(int $id): bool
    {
        return $this->update($id, ['statut' => 'validee']);
    }

    public function rejeter(int $id): bool
    {
        return $this->update($id, ['statut' => 'rejetee']);
    }

    public function estInscrit(int $etudiantId, int $anneeId): bool
    {
        return $this->model->where('etudiant_id', $etudiantId)
            ->where('annee_academique_id', $anneeId)
            ->exists();
    }

    public function getStatistiquesByAnnee(int $anneeId): array
    {
        $total = $this->model->where('annee_academique_id', $anneeId)->count();
        $enAttente = $this->model->where('annee_academique_id', $anneeId)
            ->where('statut', 'en_attente')->count();
        $validees = $this->model->where('annee_academique_id', $anneeId)
            ->where('statut', 'validee')->count();
        $rejetees = $this->model->where('annee_academique_id', $anneeId)
            ->where('statut', 'rejetee')->count();

        return [
            'total' => $total,
            'en_attente' => $enAttente,
            'validees' => $validees,
            'rejetees' => $rejetees,
            'taux_validation' => $total > 0 ? round(($validees / $total) * 100, 2) : 0
        ];
    }
}
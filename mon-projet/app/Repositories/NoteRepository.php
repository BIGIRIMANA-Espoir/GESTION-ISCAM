<?php

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Support\Collection;

class NoteRepository extends BaseRepository
{
    /**
     * NoteRepository constructor.
     */
    public function __construct(Note $note)
    {
        parent::__construct($note);
    }

    /**
     * AJOUT CRITIQUE : Méthode countByTeacher pour corriger l'erreur VS Code
     */
    public function countByTeacher(int $enseignantId): int
    {
        return $this->model->whereHas('cours', function($query) use ($enseignantId) {
            $query->where('enseignant_id', $enseignantId);
        })->count();
    }

    /**
     * Récupérer les notes par étudiant
     */
    public function getByEtudiant(int $etudiantId): Collection
    {
        return $this->model->where('etudiant_id', $etudiantId)
            ->with('cours')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Récupérer les notes par cours
     */
    public function getByCours(int $coursId): Collection
    {
        return $this->model->where('cours_id', $coursId)
            ->with('etudiant')
            ->orderBy('note', 'desc')
            ->get();
    }

    /**
     * Calculer la moyenne d'un étudiant
     */
    public function getMoyenneByEtudiant(int $etudiantId): float
    {
        return (float) ($this->model->where('etudiant_id', $etudiantId)->avg('note') ?? 0);
    }

    /**
     * Calculer la moyenne d'un cours
     */
    public function getMoyenneByCours(int $coursId): float
    {
        return (float) ($this->model->where('cours_id', $coursId)->avg('note') ?? 0);
    }

    /**
     * Taux de réussite par cours
     */
    public function getReussiteParCours(int $coursId): array
    {
        $notes = $this->getByCours($coursId);
        $total = $notes->count();
        
        if ($total === 0) {
            return ['total' => 0, 'reussite' => 0, 'taux_reussite' => 0];
        }

        $reussite = $notes->where('note', '>=', 10)->count();

        return [
            'total' => $total,
            'reussite' => $reussite,
            'taux_reussite' => round(($reussite / $total) * 100, 2)
        ];
    }

    /**
     * Vérifier si une note existe déjà
     */
    public function noteExiste(int $etudiantId, int $coursId, string $session = 'normale'): bool
    {
        return $this->model->where('etudiant_id', $etudiantId)
            ->where('cours_id', $coursId)
            ->where('session', $session)
            ->exists();
    }

    /**
     * Statistiques générales
     */
    public function getStatistiquesGenerales(): array
    {
        $total = $this->model->count();
        return [
            'total_notes' => $total,
            'moyenne_globale' => round((float)$this->model->avg('note'), 2),
            'taux_reussite_global' => $total > 0 
                ? round(($this->model->where('note', '>=', 10)->count() / $total) * 100, 2)
                : 0
        ];
    }
}

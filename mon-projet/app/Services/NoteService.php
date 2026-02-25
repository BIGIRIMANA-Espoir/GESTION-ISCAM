<?php

namespace App\Services;

use App\Repositories\NoteRepository;
use App\Repositories\EtudiantRepository;
use App\Repositories\CoursRepository;
use App\Models\Note;
use App\Models\Notification;
use Illuminate\Support\Collection;

class NoteService extends BaseService
{
    protected NoteRepository $noteRepository;
    protected EtudiantRepository $etudiantRepository;
    protected CoursRepository $coursRepository;

    /**
     * NoteService constructor.
     */
    public function __construct(
        NoteRepository $noteRepository,
        EtudiantRepository $etudiantRepository,
        CoursRepository $coursRepository
    ) {
        parent::__construct($noteRepository);
        $this->noteRepository = $noteRepository;
        $this->etudiantRepository = $etudiantRepository;
        $this->coursRepository = $coursRepository;
    }

    /**
     * Get notes by student
     */
    public function getByStudent(int $etudiantId): Collection
    {
        return $this->noteRepository->getByEtudiant($etudiantId);
    }

    /**
     * Get notes by course
     */
    public function getByCourse(int $coursId): Collection
    {
        return $this->noteRepository->getByCours($coursId);
    }

    /**
     * Get student average
     */
    public function getStudentAverage(int $etudiantId): float
    {
        return (float) ($this->noteRepository->getMoyenneByEtudiant($etudiantId) ?? 0);
    }

    /**
     * Get course average
     */
    public function getCourseAverage(int $coursId): float
    {
        return (float) ($this->noteRepository->getMoyenneByCours($coursId) ?? 0);
    }

    /**
     * Get course success rate
     */
    public function getCourseSuccessRate(int $coursId): array
    {
        return $this->noteRepository->getReussiteParCours($coursId);
    }

    /**
     * Enter grade with notification
     */
    public function enterGrade(array $data): Note
    {
        $this->validateGradeData($data);
        
        $etudiant = $this->etudiantRepository->find($data['etudiant_id']);
        $cours = $this->coursRepository->find($data['cours_id']);
        
        if (!$etudiant || !$cours) {
            throw new \InvalidArgumentException("Étudiant ou Cours introuvable");
        }
        
        if ($this->noteRepository->noteExiste($data['etudiant_id'], $data['cours_id'], $data['session'] ?? 'normale')) {
            throw new \InvalidArgumentException("Une note existe déjà pour cet étudiant dans ce cours");
        }
        
        $note = $this->create($data);
        
        if ($etudiant->user_id) {
            Notification::create([
                'user_id' => $etudiant->user_id,
                'type' => 'info',
                'titre' => 'Nouvelle note publiée',
                'message' => "Votre note en {$cours->nom_cours} est : {$data['note']}/20",
                'lien' => '/etudiant/notes',
                'lu' => false
            ]);
        }
        
        return $note;
    }

    /**
     * Get student transcript
     */
    public function getStudentTranscript(int $etudiantId): array
    {
        $etudiant = $this->etudiantRepository->find($etudiantId);
        $notes = $this->getByStudent($etudiantId);
        $notesParCours = $notes->groupBy('cours.nom_cours');
        
        $transcript = [
            'etudiant' => [
                'nom' => $etudiant->nom ?? '',
                'prenom' => $etudiant->prenom ?? '',
                'matricule' => $etudiant->matricule ?? ''
            ],
            'cours' => []
        ];
        
        foreach ($notesParCours as $coursNom => $notesCours) {
            $transcript['cours'][] = [
                'nom' => $coursNom,
                'notes' => $notesCours->pluck('note'),
                'moyenne' => (float) ($notesCours->avg('note') ?? 0),
                'credits' => $notesCours->first()->cours->credits ?? 0
            ];
        }
        
        $transcript['moyenne_generale'] = (float) ($notes->avg('note') ?? 0);
        $transcript['total_credits'] = $notes->sum('cours.credits');
        
        return $transcript;
    }

    /**
     * Get general statistics
     */
    public function getStatistiquesGenerales(): array
    {
        return $this->noteRepository->getStatistiquesGenerales();
    }

    /**
     * Get overall average of all notes
     *
     * @return float
     */
    public function getMoyenneGenerale(): float
    {
        $stats = $this->getStatistiquesGenerales();
        return (float) ($stats['moyenne_globale'] ?? 0);
    }

    /**
     * Get global success rate
     * AJOUTÉ POUR STATISTIQUES
     *
     * @return float
     */
    public function getTauxReussiteGlobal(): float
    {
        $stats = $this->getStatistiquesGenerales();
        return (float) ($stats['taux_reussite_global'] ?? 0);
    }

    /**
     * Count notes by teacher
     */
    public function countByTeacher(int $enseignantId): int
    {
        return $this->noteRepository->countByTeacher($enseignantId);
    }

    /**
     * Validate grade data
     */
    protected function validateGradeData(array $data): void
    {
        if (empty($data['etudiant_id']) || empty($data['cours_id'])) {
            throw new \InvalidArgumentException("ID Étudiant et ID Cours requis");
        }
        
        if (!isset($data['note']) || !is_numeric($data['note']) || $data['note'] < 0 || $data['note'] > 20) {
            throw new \InvalidArgumentException("La note doit être comprise entre 0 et 20");
        }
        
        if (empty($data['session']) || !in_array($data['session'], ['normale', 'rattrapage'])) {
            throw new \InvalidArgumentException("Type de session invalide");
        }
    }
}
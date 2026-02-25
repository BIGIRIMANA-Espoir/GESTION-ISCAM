@extends('layouts.dashboard')

@section('title', 'Détails de la Note')
@section('page-title', 'Fiche de Note')

@section('menu')
    <a href="{{ route('admin.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('admin.etudiants.index') }}">
        <i class="fas fa-users"></i> Étudiants
    </a>
    <a href="{{ route('admin.enseignants.index') }}">
        <i class="fas fa-chalkboard-teacher"></i> Enseignants
    </a>
    <a href="{{ route('admin.cours.index') }}">
        <i class="fas fa-book"></i> Cours
    </a>
    <a href="{{ route('admin.inscriptions.index') }}">
        <i class="fas fa-pen-to-square"></i> Inscriptions
    </a>
    <a href="{{ route('admin.notes.index') }}" class="active">
        <i class="fas fa-chart-line"></i> Notes
    </a>
    <a href="{{ route('admin.statistiques.index') }}">
        <i class="fas fa-chart-bar"></i> Statistiques
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-chart-line text-primary me-2"></i>
                                Note #{{ $note->id }}
                            </h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-info">{{ $note->session ?? 'normale' }}</span>
                                <span class="ms-2">
                                    <i class="fas fa-calendar me-1"></i> 
                                    {{ $note->created_at ? $note->created_at->format('d/m/Y H:i') : 'N/A' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.notes.edit', $note->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="row">
        <!-- Colonne gauche - Étudiant -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate text-primary me-2"></i>
                        Étudiant
                    </h5>
                </div>
                <div class="card-body">
                    @if($note->etudiant)
                        <div class="text-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block">
                                <i class="fas fa-user-graduate fa-3x text-primary"></i>
                            </div>
                        </div>
                        
                        <h5 class="text-center">
                            <a href="{{ route('admin.etudiants.show', $note->etudiant->id) }}">
                                {{ $note->etudiant->prenom }} {{ $note->etudiant->nom }}
                            </a>
                        </h5>
                        
                        <table class="table table-sm mt-3">
                            <tr>
                                <th>Matricule</th>
                                <td><span class="badge bg-secondary">{{ $note->etudiant->matricule }}</span></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <a href="mailto:{{ $note->etudiant->email }}">
                                        <small>{{ $note->etudiant->email }}</small>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Département</th>
                                <td>{{ $note->etudiant->departement->nom_departement ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">Étudiant non trouvé</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne milieu - Cours -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book text-success me-2"></i>
                        Cours
                    </h5>
                </div>
                <div class="card-body">
                    @if($note->cours)
                        <div class="text-center mb-3">
                            <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block">
                                <i class="fas fa-book fa-3x text-success"></i>
                            </div>
                        </div>
                        
                        <h5 class="text-center">
                            <a href="{{ route('admin.cours.show', $note->cours->id) }}">
                                {{ $note->cours->nom_cours }}
                            </a>
                        </h5>
                        
                        <table class="table table-sm mt-3">
                            <tr>
                                <th>Code</th>
                                <td><span class="badge bg-secondary">{{ $note->cours->code_cours }}</span></td>
                            </tr>
                            <tr>
                                <th>Crédits</th>
                                <td><span class="badge bg-primary">{{ $note->cours->credits }}</span></td>
                            </tr>
                            <tr>
                                <th>Enseignant</th>
                                <td>
                                    @if($note->cours->enseignant)
                                        <a href="{{ route('admin.enseignants.show', $note->cours->enseignant->id) }}">
                                            {{ $note->cours->enseignant->prenom }} {{ $note->cours->enseignant->nom }}
                                        </a>
                                    @else
                                        <span class="text-muted">Non assigné</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">Cours non trouvé</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Note -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-warning me-2"></i>
                        Détails de la note
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="display-1 fw-bold {{ ($note->note ?? 0) >= 10 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($note->note ?? 0, 2) }}<small class="fs-4">/20</small>
                        </div>
                        
                        @if(($note->note ?? 0) >= 16)
                            <span class="badge bg-success fs-6">Excellent</span>
                        @elseif(($note->note ?? 0) >= 14)
                            <span class="badge bg-info fs-6">Très bien</span>
                        @elseif(($note->note ?? 0) >= 12)
                            <span class="badge bg-primary fs-6">Bien</span>
                        @elseif(($note->note ?? 0) >= 10)
                            <span class="badge bg-secondary fs-6">Passable</span>
                        @else
                            <span class="badge bg-danger fs-6">Insuffisant</span>
                        @endif
                    </div>
                    
                    <table class="table">
                        <tr>
                            <th>Session</th>
                            <td>
                                @if(($note->session ?? 'normale') == 'normale')
                                    <span class="badge bg-info">Normale</span>
                                @else
                                    <span class="badge bg-warning">Rattrapage</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Année académique</th>
                            <td><span class="badge bg-secondary">{{ $note->annee_academique ?? 'N/A' }}</span></td>
                        </tr>
                        <tr>
                            <th>Date saisie</th>
                            <td>{{ $note->created_at ? $note->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Dernière modif</th>
                            <td>{{ $note->updated_at ? $note->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques comparatives (CORRIGÉ AVEC SÉCURITÉ) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-info me-2"></i>
                        Positionnement de l'étudiant
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            // Valeurs sécurisées
                            $moyenneEtudiant = $statsEtudiant['moyenne'] ?? 0;
                            $totalNotesEtudiant = $statsEtudiant['total_notes'] ?? 0;
                            
                            // Moyenne du cours - on essaie plusieurs clés possibles
                            $moyenneCours = $statsCours['moyenne_classe'] ?? $statsCours['moyenne'] ?? 0;
                            $totalEtudiantsCours = $statsCours['total'] ?? 0;
                            $reussiteCours = $statsCours['reussite'] ?? 0;
                            $echecCours = $statsCours['echec'] ?? 0;
                            
                            // Calcul de l'écart
                            $ecart = ($note->note ?? 0) - $moyenneCours;
                        @endphp
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Moyenne de l'étudiant</h6>
                                    <h3 class="{{ $moyenneEtudiant >= 10 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($moyenneEtudiant, 2) }}/20
                                    </h3>
                                    <small>{{ $totalNotesEtudiant }} note(s)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Moyenne du cours</h6>
                                    <h3 class="{{ $moyenneCours >= 10 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($moyenneCours, 2) }}/20
                                    </h3>
                                    <small>{{ $totalEtudiantsCours }} étudiant(s)</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Écart</h6>
                                    <h3 class="{{ $ecart >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 2) }}
                                    </h3>
                                    <small>par rapport à la moyenne</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Barre de progression comparative -->
                    <div class="mt-4">
                        <h6>Distribution des notes du cours</h6>
                        <div class="progress" style="height: 30px;">
                            @php
                                $total = $totalEtudiantsCours ?: 1; // Éviter division par zéro
                                $pourcentageReussite = ($reussiteCours / $total) * 100;
                                $pourcentageEchec = ($echecCours / $total) * 100;
                            @endphp
                            <div class="progress-bar bg-success" 
                                 style="width: {{ $pourcentageReussite }}%">
                                Réussite {{ $reussiteCours }}
                            </div>
                            <div class="progress-bar bg-danger" 
                                 style="width: {{ $pourcentageEchec }}%">
                                Échec {{ $echecCours }}
                            </div>
                        </div>
                        <div class="text-center mt-2">
                            <span class="badge bg-primary">Position de l'étudiant: 
                                @php
                                    $position = $totalEtudiantsCours > 0 
                                        ? round(($moyenneEtudiant / 20) * 100) 
                                        : 0;
                                @endphp
                                {{ $position }}% ({{ $moyenneEtudiant >= $moyenneCours ? 'Au-dessus' : 'En-dessous' }} de la moyenne)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des notes de l'étudiant pour ce cours -->
    @if($note->etudiant)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-warning me-2"></i>
                        Historique des notes - {{ $note->etudiant->prenom }} {{ $note->etudiant->nom }}
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $historique = $note->etudiant->notes()
                            ->with('cours')
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp
                    
                    @if($historique->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Cours</th>
                                        <th>Note</th>
                                        <th>Session</th>
                                        <th>Année</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historique as $hist)
                                    <tr class="{{ $hist->id == $note->id ? 'table-primary' : '' }}">
                                        <td>
                                            <small>{{ $hist->cours->code_cours ?? '' }}</small>
                                            <br>
                                            <strong>{{ $hist->cours->nom_cours ?? '' }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ ($hist->note ?? 0) >= 10 ? 'success' : 'danger' }}">
                                                {{ number_format($hist->note ?? 0, 2) }}/20
                                            </span>
                                        </td>
                                        <td>{{ $hist->session ?? 'N/A' }}</td>
                                        <td>{{ $hist->annee_academique ?? 'N/A' }}</td>
                                        <td>{{ $hist->created_at ? $hist->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun historique</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Animation simple pour la note
    document.addEventListener('DOMContentLoaded', function() {
        const noteElement = document.querySelector('.display-1');
        if (noteElement) {
            noteElement.style.transform = 'scale(0.5)';
            noteElement.style.transition = 'transform 0.5s ease';
            setTimeout(() => {
                noteElement.style.transform = 'scale(1)';
            }, 100);
        }
    });
</script>
@endpush
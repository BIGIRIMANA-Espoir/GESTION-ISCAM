@extends('layouts.dashboard')

@section('title', 'Détails du Cours')
@section('page-title', 'Fiche du Cours')

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
    <a href="{{ route('admin.cours.index') }}" class="active">
        <i class="fas fa-book"></i> Cours
    </a>
    <a href="{{ route('admin.inscriptions.index') }}">
        <i class="fas fa-pen-to-square"></i> Inscriptions
    </a>
    <a href="{{ route('admin.notes.index') }}">
        <i class="fas fa-chart-line"></i> Notes
    </a>
    <a href="{{ route('admin.statistiques.index') }}">
        <i class="fas fa-chart-bar"></i> Statistiques
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête avec boutons d'action -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-book text-primary me-2"></i>
                                {{ $cours->nom_cours }}
                            </h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-secondary">{{ $cours->code_cours }}</span>
                                <span class="ms-2">
                                    <i class="fas fa-layer-group me-1"></i> {{ $cours->credits }} crédits
                                </span>
                                @if($cours->departement)
                                <span class="ms-2">
                                    <i class="fas fa-building me-1"></i> {{ $cours->departement->nom_departement }}
                                </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.cours.edit', $cours->id) }}" 
                               class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <a href="{{ route('admin.notes.create', ['cours_id' => $cours->id]) }}" 
                               class="btn btn-success">
                                <i class="fas fa-plus me-1"></i> Ajouter notes
                            </a>
                            <a href="{{ route('admin.cours.index') }}" 
                               class="btn btn-secondary">
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
        <!-- Colonne gauche -->
        <div class="col-md-4 mb-4">
            <!-- Carte informations générales -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Informations générales
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Code</th>
                            <td><span class="badge bg-secondary">{{ $cours->code_cours }}</span></td>
                        </tr>
                        <tr>
                            <th>Nom complet</th>
                            <td><strong>{{ $cours->nom_cours }}</strong></td>
                        </tr>
                        <tr>
                            <th>Crédits</th>
                            <td><span class="badge bg-primary">{{ $cours->credits }} crédits</span></td>
                        </tr>
                        <tr>
                            <th>Département</th>
                            <td>
                                @if($cours->departement)
                                    {{ $cours->departement->nom_departement }}
                                @else
                                    <span class="text-muted">Non affecté</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Enseignant</th>
                            <td>
                                @if($cours->enseignant)
                                    <a href="{{ route('admin.enseignants.show', $cours->enseignant->id) }}">
                                        {{ $cours->enseignant->prenom }} {{ $cours->enseignant->nom }}
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $cours->enseignant->grade }}</small>
                                @else
                                    <span class="text-muted">Non assigné</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Semestre</th>
                            <td>{{ $cours->semestre ?? 'Non spécifié' }}</td>
                        </tr>
                        <tr>
                            <th>Niveau</th>
                            <td>{{ $cours->niveau ?? 'Non spécifié' }}</td>
                        </tr>
                        <tr>
                            <th>Langue</th>
                            <td>
                                @if($cours->langue == 'fr')
                                    <i class="fas fa-language me-1"></i> Français
                                @elseif($cours->langue == 'en')
                                    <i class="fas fa-language me-1"></i> Anglais
                                @elseif($cours->langue == 'bi')
                                    <i class="fas fa-language me-1"></i> Kirundi
                                @else
                                    Non spécifié
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Carte horaires -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-success me-2"></i>
                        Volume horaire
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <h4>{{ $cours->heures_cours ?? 0 }}</h4>
                                <small>Cours</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <h4>{{ $cours->heures_td ?? 0 }}</h4>
                                <small>TD</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 bg-light rounded">
                                <h4>{{ $cours->heures_tp ?? 0 }}</h4>
                                <small>TP</small>
                            </div>
                        </div>
                    </div>
                    @if(($cours->heures_cours ?? 0) + ($cours->heures_td ?? 0) + ($cours->heures_tp ?? 0) > 0)
                        <div class="mt-3 text-center">
                            <span class="badge bg-info">
                                Total: {{ ($cours->heures_cours ?? 0) + ($cours->heures_td ?? 0) + ($cours->heures_tp ?? 0) }} heures
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Carte options -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-toggle-on text-warning me-2"></i>
                        Options du cours
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       {{ $cours->obligatoire ? 'checked' : '' }} disabled>
                                <label class="form-check-label">
                                    Cours obligatoire
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       {{ $cours->avec_td ? 'checked' : '' }} disabled>
                                <label class="form-check-label">
                                    Avec TD
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       {{ $cours->avec_tp ? 'checked' : '' }} disabled>
                                <label class="form-check-label">
                                    Avec TP
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       {{ $cours->examen_final ? 'checked' : '' }} disabled>
                                <label class="form-check-label">
                                    Examen final
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-md-8 mb-4">
            <!-- Carte description -->
            @if($cours->description)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-align-left text-info me-2"></i>
                        Description
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $cours->description }}</p>
                </div>
            </div>
            @endif

            <!-- Carte objectifs -->
            @if($cours->objectifs)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bullseye text-success me-2"></i>
                        Objectifs d'apprentissage
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $cours->objectifs }}</p>
                </div>
            </div>
            @endif

            <!-- Carte prérequis -->
            @if($cours->prerequis)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Prérequis
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $cours->prerequis }}</p>
                </div>
            </div>
            @endif

            <!-- Carte bibliographie -->
            @if($cours->bibliographie)
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book-open text-primary me-2"></i>
                        Bibliographie
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $cours->bibliographie }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Statistiques du cours -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-danger me-2"></i>
                        Statistiques du cours
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $cours->inscriptions?->count() ?? 0 }}</h3>
                                    <small>Étudiants inscrits</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $cours->notes?->count() ?? 0 }}</h3>
                                    <small>Notes saisies</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    @php
                                        $moyenne = $cours->notes?->avg('note') ?? 0;
                                    @endphp
                                    <h3>{{ $moyenne > 0 ? number_format($moyenne, 2) : 0 }}/20</h3>
                                    <small>Moyenne générale</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $statistiques['taux_reussite'] ?? 0 }}%</h3>
                                    <small>Taux de réussite</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(($cours->notes?->count() ?? 0) > 0)
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <canvas id="repartitionChart"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="evolutionChart"></canvas>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants inscrits -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users text-primary me-2"></i>
                            Étudiants inscrits ({{ $cours->inscriptions?->count() ?? 0 }})
                        </h5>
                        <a href="{{ route('admin.notes.create', ['cours_id' => $cours->id]) }}" 
                           class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Saisir notes
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(($cours->inscriptions?->count() ?? 0) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Étudiant</th>
                                        <th>Note</th>
                                        <th>Session</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cours->inscriptions as $inscription)
                                    @if($inscription->etudiant)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $inscription->etudiant->matricule }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.etudiants.show', $inscription->etudiant->id) }}">
                                                {{ $inscription->etudiant->prenom }} {{ $inscription->etudiant->nom }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $note = $cours->notes?->where('etudiant_id', $inscription->etudiant->id)->first();
                                            @endphp
                                            @if($note)
                                                <span class="badge bg-{{ $note->note >= 10 ? 'success' : 'danger' }}">
                                                    {{ $note->note }}/20
                                                </span>
                                                <small class="text-muted">({{ $note->session }})</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($note)
                                                {{ $note->session }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($note)
                                                <a href="{{ route('admin.notes.edit', $note->id) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.notes.create', ['cours_id' => $cours->id, 'etudiant_id' => $inscription->etudiant->id]) }}" 
                                                   class="btn btn-sm btn-success">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun étudiant inscrit pour le moment</p>
                            <a href="{{ route('admin.inscriptions.create', ['cours_id' => $cours->id]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Inscrire des étudiants
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(($cours->notes?->count() ?? 0) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique de répartition des notes
    const repartitionCtx = document.getElementById('repartitionChart').getContext('2d');
    new Chart(repartitionCtx, {
        type: 'pie',
        data: {
            labels: ['Réussite (≥10)', 'Échec (<10)'],
            datasets: [{
                data: [{{ $statistiques['reussite'] ?? 0 }}, {{ $statistiques['echec'] ?? 0 }}],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        }
    });

    // Graphique d'évolution (simulé)
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: ['Sem1', 'Sem2', 'Sem3', 'Sem4'],
            datasets: [{
                label: 'Moyenne du cours',
                data: [12.5, 13.2, 14.1, {{ $statistiques['moyenne'] ?? 0 }}],
                borderColor: '#007bff',
                tension: 0.1
            }]
        }
    });
</script>
@endif
@endpush
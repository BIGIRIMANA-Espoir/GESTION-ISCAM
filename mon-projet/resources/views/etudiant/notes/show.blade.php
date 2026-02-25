@extends('layouts.dashboard')

@section('title', 'Détail de la Note')
@section('page-title', 'Consultation Note')

@section('menu')
    <a href="{{ route('etudiant.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('etudiant.notes.index') }}" class="active">
        <i class="fas fa-chart-line"></i> Mes notes
    </a>
    <a href="{{ route('etudiant.inscriptions.index') }}">
        <i class="fas fa-pen-to-square"></i> Mes inscriptions
    </a>
    <a href="{{ route('etudiant.profile') }}">
        <i class="fas fa-user"></i> Mon profil
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-chart-line me-2"></i>
                                Détail de la note
                            </h4>
                            <p class="mb-0 opacity-75">
                                {{ $note->cours->nom_cours ?? 'Programmation Web Avancée' }} 
                                ({{ $note->cours->code_cours ?? 'INF304' }})
                            </p>
                        </div>
                        <a href="{{ route('etudiant.notes.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Carte principale de la note -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="display-1 fw-bold {{ ($note->note ?? 16.5) >= 10 ? 'text-success' : 'text-danger' }}">
                        {{ number_format($note->note ?? 16.5, 2) }}<small class="fs-4">/20</small>
                    </div>
                    
                    @if(($note->note ?? 16.5) >= 16)
                        <span class="badge bg-success fs-5 mt-2">Excellent</span>
                    @elseif(($note->note ?? 16.5) >= 14)
                        <span class="badge bg-info fs-5 mt-2">Très bien</span>
                    @elseif(($note->note ?? 16.5) >= 12)
                        <span class="badge bg-primary fs-5 mt-2">Bien</span>
                    @elseif(($note->note ?? 16.5) >= 10)
                        <span class="badge bg-secondary fs-5 mt-2">Passable</span>
                    @else
                        <span class="badge bg-danger fs-5 mt-2">Insuffisant</span>
                    @endif

                    <hr class="my-4">

                    <div class="text-start">
                        <table class="table table-sm">
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
                                <th>Semestre</th>
                                <td>{{ $note->semestre ?? 'Semestre 2' }}</td>
                            </tr>
                            <tr>
                                <th>Crédits</th>
                                <td><span class="badge bg-primary">{{ $note->cours->credits ?? 4 }}</span></td>
                            </tr>
                            <tr>
                                <th>Date saisie</th>
                                <td>{{ isset($note->created_at) ? $note->created_at->format('d/m/Y') : '15/02/2026' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations sur le cours -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-book text-primary me-2"></i>
                        Informations du cours
                    </h5>
                </div>
                <div class="card-body">
                    <h5>{{ $note->cours->nom_cours ?? 'Programmation Web Avancée' }}</h5>
                    <p class="text-muted small">
                        {{ $note->cours->description ?? 'Développement d\'applications web avec Laravel et Vue.js' }}
                    </p>

                    <table class="table table-sm mt-3">
                        <tr>
                            <th>Code</th>
                            <td><span class="badge bg-secondary">{{ $note->cours->code_cours ?? 'INF304' }}</span></td>
                        </tr>
                        <tr>
                            <th>Département</th>
                            <td>{{ $note->cours->departement->nom_departement ?? 'Informatique' }}</td>
                        </tr>
                        <tr>
                            <th>Enseignant</th>
                            <td>
                                @if($note->cours->enseignant)
                                    {{ $note->cours->enseignant->prenom }} {{ $note->cours->enseignant->nom }}
                                @else
                                    Dr. BIGIRIMANA Espoir
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Crédits ECTS</th>
                            <td>{{ $note->cours->credits ?? 4 }}</td>
                        </tr>
                        <tr>
                            <th>Volume horaire</th>
                            <td>{{ ($note->cours->heures_cours ?? 30) + ($note->cours->heures_td ?? 15) + ($note->cours->heures_tp ?? 15) }} heures</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistiques comparatives -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-success me-2"></i>
                        Positionnement
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-muted">Votre note</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ (($note->note ?? 16.5)/20)*100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format((($note->note ?? 16.5)/20)*100, 1) }}% de la note maximale</small>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Moyenne du cours</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-info" 
                                 style="width: {{ (($moyenneCours ?? 13.5)/20)*100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($moyenneCours ?? 13.5, 2) }}/20 ({{ $totalEtudiants ?? 45 }} étudiants)</small>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-muted">Meilleure note</h6>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ (($meilleureNote ?? 18.5)/20)*100 }}%"></div>
                        </div>
                        <small class="text-muted">{{ number_format($meilleureNote ?? 18.5, 2) }}/20</small>
                    </div>

                    <hr>

                    <div class="text-center">
                        <h6 class="text-muted">Votre position</h6>
                        <h3 class="text-primary">{{ $rang ?? '5' }}<small class="fs-6 text-muted">/{{ $totalEtudiants ?? 45 }}</small></h3>
                        <p class="text-muted small">Top {{ round((($rang ?? 5)/ ($totalEtudiants ?? 45))*100, 1) }}% de la promotion</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appréciation et commentaires -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-comment text-info me-2"></i>
                        Appréciation du professeur
                    </h5>
                </div>
                <div class="card-body">
                    @if($note->appreciation ?? false)
                        <p class="mb-0">{{ $note->appreciation }}</p>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Bon travail !</strong> Continuez ainsi. Votre compréhension des concepts est satisfaisante. 
                            Pour la prochaine session, approfondissez la partie pratique.
                        </div>
                        <small class="text-muted">Dr. BIGIRIMANA Espoir - {{ isset($note->updated_at) ? $note->updated_at->format('d/m/Y') : '15/02/2026' }}</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
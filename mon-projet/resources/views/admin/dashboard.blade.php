@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', 'Tableau de Bord Administrateur')

@section('menu')
    <a href="{{ route('admin.dashboard') }}" class="active">
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
    <a href="{{ route('admin.notes.index') }}">
        <i class="fas fa-chart-line"></i> Notes
    </a>
    <a href="{{ route('admin.statistiques.index') }}">
        <i class="fas fa-chart-bar"></i> Statistiques
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Cartes statistiques principales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Étudiants</h6>
                            <h2 class="mb-0">{{ $stats['total_etudiants'] ?? 1234 }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i> +12% ce mois
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Enseignants</h6>
                            <h2 class="mb-0">{{ $stats['total_enseignants'] ?? 89 }}</h2>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-3x opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i> +5% cette année
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Total Cours</h6>
                            <h2 class="mb-0">{{ $stats['total_cours'] ?? 156 }}</h2>
                        </div>
                        <i class="fas fa-book fa-3x opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-minus me-1"></i> Stable
                        </small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-1">Inscriptions en attente</h6>
                            <h2 class="mb-0">{{ $stats['inscriptions_attente'] ?? 23 }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-50"></i>
                    </div>
                    <div class="mt-3">
                        <small class="text-white-50">
                            <i class="fas fa-arrow-up me-1"></i> +3 aujourd'hui
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques et statistiques -->
    <div class="row mb-4">
        <div class="col-xl-8 col-lg-7">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        Évolution des inscriptions
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="inscriptionsChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-success me-2"></i>
                        Répartition par département
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="deptChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières inscriptions et activités -->
    <div class="row">
        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-pen-to-square text-warning me-2"></i>
                            Dernières inscriptions
                        </h5>
                        <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-sm btn-primary">
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(($stats['dernieres_inscriptions'] ?? collect())->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($stats['dernieres_inscriptions'] as $inscription)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">
                                                <a href="{{ route('admin.etudiants.show', $inscription->etudiant_id) }}">
                                                    {{ $inscription->etudiant->prenom ?? '' }} {{ $inscription->etudiant->nom ?? '' }}
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                {{ $inscription->anneeAcademique->annee ?? '' }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $inscription->statut == 'validee' ? 'success' : ($inscription->statut == 'en_attente' ? 'warning' : 'danger') }}">
                                                {{ $inscription->statut }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ $inscription->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">Aucune inscription récente</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-bell text-danger me-2"></i>
                            Notifications récentes
                        </h5>
                        <a href="#" class="btn btn-sm btn-primary">
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(($notifications ?? collect())->count() > 0)
                        @foreach($notifications as $notif)
                            <div class="alert alert-{{ $notif['type'] }} mb-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $notif['titre'] }}</strong>
                                        <p class="mb-0 small">{{ $notif['message'] }}</p>
                                    </div>
                                    <small class="text-muted">{{ $notif['time'] }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Notifications par défaut -->
                        <div class="alert alert-success mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Nouvelle inscription</strong>
                                    <p class="mb-0 small">Jean Ndayishimiye - L1 Informatique</p>
                                </div>
                                <small class="text-muted">Il y a 5 min</small>
                            </div>
                        </div>
                        <div class="alert alert-info mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Cours ajouté</strong>
                                    <p class="mb-0 small">Programmation Web - L3</p>
                                </div>
                                <small class="text-muted">Il y a 2 heures</small>
                            </div>
                        </div>
                        <div class="alert alert-warning mb-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>Réunion pédagogique</strong>
                                    <p class="mb-0 small">Demain à 10h en salle 203</p>
                                </div>
                                <small class="text-muted">Il y a 1 jour</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides des cours -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy text-warning me-2"></i>
                        Top 5 des meilleures moyennes par cours
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Code</th>
                                    <th>Département</th>
                                    <th>Moyenne</th>
                                    <th>Taux réussite</th>
                                    <th>Étudiants</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(($stats['top_cours'] ?? []) as $cours)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.cours.show', $cours->id) }}">
                                            {{ $cours->nom_cours }}
                                        </a>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $cours->code_cours }}</span></td>
                                    <td>{{ $cours->departement->nom_departement ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ ($cours->notes_avg_note ?? 0) >= 10 ? 'success' : 'danger' }}">
                                            {{ number_format($cours->notes_avg_note ?? 0, 2) }}/20
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $total = $cours->notes_count ?? 0;
                                            $reussite = $cours->notes_reussite_count ?? 0;
                                            $taux = $total > 0 ? round(($reussite / $total) * 100, 1) : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" 
                                                 style="width: {{ $taux }}%">
                                                {{ $taux }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $cours->inscriptions_count ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique d'évolution des inscriptions
    const ctx1 = document.getElementById('inscriptionsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Inscriptions 2026',
                data: [65, 78, 90, 105, 120, 135, 140, 145, 180, 210, 230, 250],
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Graphique de répartition par département
    const ctx2 = document.getElementById('deptChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Informatique', 'Mathématiques', 'Physique', 'Chimie', 'Biologie'],
            datasets: [{
                data: [45, 25, 15, 10, 5],
                backgroundColor: ['#667eea', '#28a745', '#ffc107', '#17a2b8', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
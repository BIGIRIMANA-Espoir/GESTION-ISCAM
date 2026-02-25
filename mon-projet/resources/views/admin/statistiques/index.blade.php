@extends('layouts.dashboard')

@section('title', 'Statistiques')
@section('page-title', 'Tableau de Bord Statistiques')

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
    <a href="{{ route('admin.notes.index') }}">
        <i class="fas fa-chart-line"></i> Notes
    </a>
    <a href="{{ route('admin.statistiques.index') }}" class="active">
        <i class="fas fa-chart-bar"></i> Statistiques
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Statistiques générales de l'ISCAM
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes récapitulatives -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['etudiants']['total'] }}</h3>
                    <small>Étudiants</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['enseignants']['total'] }}</h3>
                    <small>Enseignants</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['cours']['total'] }}</h3>
                    <small>Cours</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['inscriptions']['total'] }}</h3>
                    <small>Inscriptions</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Répartition des étudiants par département
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="etudiantsChart" style="width:100%; height:300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Évolution des inscriptions
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" style="width:100%; height:300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Détails -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt text-info me-2"></i>
                        Inscriptions par année
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Année</th>
                                    <th>Total</th>
                                    <th>En attente</th>
                                    <th>Validées</th>
                                    <th>Rejetées</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['inscriptions']['par_annee'] as $annee => $data)
                                <tr>
                                    <td><strong>{{ $annee }}</strong></td>
                                    <td>{{ $data['total'] }}</td>
                                    <td>{{ $data['en_attente'] }}</td>
                                    <td>{{ $data['validees'] }}</td>
                                    <td>{{ $data['rejetees'] }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucune donnée disponible</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-warning me-2"></i>
                        Statistiques des notes
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Total notes</th>
                            <td>{{ $stats['notes']['total_notes'] }}</td>
                        </tr>
                        <tr>
                            <th>Moyenne générale</th>
                            <td>
                                <span class="badge bg-primary fs-6">
                                    {{ $stats['notes']['moyenne_globale'] }}/20
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Taux de réussite global</th>
                            <td>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" 
                                         style="width: {{ $stats['reussite'] }}%; font-size: 14px;">
                                        {{ $stats['reussite'] }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique étudiants par département
    const etudiantsCtx = document.getElementById('etudiantsChart').getContext('2d');
    new Chart(etudiantsCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode(array_keys($stats['etudiants']['par_departement'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($stats['etudiants']['par_departement'])) !!},
                backgroundColor: ['#667eea', '#28a745', '#ffc107', '#17a2b8', '#dc3545']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });

    // Graphique évolution
    const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(evolutionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($stats['etudiants']['evolution']['labels']) !!},
            datasets: [{
                label: 'Nouveaux étudiants',
                data: {!! json_encode($stats['etudiants']['evolution']['data']) !!},
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true
        }
    });
</script>
@endpush
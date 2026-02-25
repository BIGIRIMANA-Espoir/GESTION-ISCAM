@extends('layouts.dashboard')

@section('title', 'Mes Notes')
@section('page-title', 'Mon Relevé de Notes')

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
                    <h4 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Mon relevé de notes - Année {{ date('Y') }}-{{ date('Y')+1 }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ number_format($stats['moyenne_generale'] ?? 13.2, 2) }}/20</h3>
                    <small>Moyenne générale</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['total_credits'] ?? 45 }}</h3>
                    <small>Crédits obtenus</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['total_cours'] ?? 8 }}</h3>
                    <small>Cours évalués</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['rang'] ?? '12/45' }}</h3>
                    <small>Rang / promotion</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des notes par semestre -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="notesTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="semestre1-tab" data-bs-toggle="tab" 
                                    data-bs-target="#semestre1" type="button" role="tab">
                                Semestre 1
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="semestre2-tab" data-bs-toggle="tab" 
                                    data-bs-target="#semestre2" type="button" role="tab">
                                Semestre 2
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="annuel-tab" data-bs-toggle="tab" 
                                    data-bs-target="#annuel" type="button" role="tab">
                                Annuel
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="notesTabContent">
                        <!-- Semestre 1 -->
                        <div class="tab-pane fade show active" id="semestre1" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Cours</th>
                                            <th>Code</th>
                                            <th>Crédits</th>
                                            <th>Note</th>
                                            <th>Session</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($notesSemestre1 ?? [] as $note)
                                        <tr>
                                            <td>{{ $note->cours->nom_cours ?? 'Programmation Web' }}</td>
                                            <td><span class="badge bg-secondary">{{ $note->cours->code_cours ?? 'INF304' }}</span></td>
                                            <td>{{ $note->cours->credits ?? 4 }}</td>
                                            <td>
                                                <span class="badge bg-{{ ($note->note ?? 14.5) >= 10 ? 'success' : 'danger' }} fs-6">
                                                    {{ number_format($note->note ?? 14.5, 2) }}/20
                                                </span>
                                            </td>
                                            <td>{{ $note->session ?? 'Normale' }}</td>
                                            <td>
                                                @if(($note->note ?? 14.5) >= 10)
                                                    <span class="badge bg-success">Validé</span>
                                                @else
                                                    <span class="badge bg-danger">Non validé</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                            <!-- Exemple statique -->
                                            <tr>
                                                <td>Programmation Web Avancée</td>
                                                <td><span class="badge bg-secondary">INF304</span></td>
                                                <td>4</td>
                                                <td><span class="badge bg-success">16.5/20</span></td>
                                                <td>Normale</td>
                                                <td><span class="badge bg-success">Validé</span></td>
                                            </tr>
                                            <tr>
                                                <td>Bases de Données</td>
                                                <td><span class="badge bg-secondary">INF202</span></td>
                                                <td>3</td>
                                                <td><span class="badge bg-success">14.0/20</span></td>
                                                <td>Normale</td>
                                                <td><span class="badge bg-success">Validé</span></td>
                                            </tr>
                                            <tr>
                                                <td>Réseaux</td>
                                                <td><span class="badge bg-secondary">INF305</span></td>
                                                <td>4</td>
                                                <td><span class="badge bg-danger">8.5/20</span></td>
                                                <td>Rattrapage</td>
                                                <td><span class="badge bg-warning">En attente</span></td>
                                            </tr>
                                        @endforelse
                                        <tr class="table-primary">
                                            <td colspan="2"><strong>Moyenne Semestre 1</strong></td>
                                            <td></td>
                                            <td><strong>{{ number_format($moyenneS1 ?? 13.0, 2) }}/20</strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Semestre 2 (similaire) -->
                        <div class="tab-pane fade" id="semestre2" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Cours</th>
                                            <th>Code</th>
                                            <th>Crédits</th>
                                            <th>Note</th>
                                            <th>Session</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Sécurité Informatique</td>
                                            <td><span class="badge bg-secondary">INF401</span></td>
                                            <td>3</td>
                                            <td><span class="badge bg-success">15.0/20</span></td>
                                            <td>Normale</td>
                                            <td><span class="badge bg-success">Validé</span></td>
                                        </tr>
                                        <tr>
                                            <td>Intelligence Artificielle</td>
                                            <td><span class="badge bg-secondary">INF402</span></td>
                                            <td>4</td>
                                            <td><span class="badge bg-success">13.5/20</span></td>
                                            <td>Normale</td>
                                            <td><span class="badge bg-success">Validé</span></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="2"><strong>Moyenne Semestre 2</strong></td>
                                            <td></td>
                                            <td><strong>14.2/20</strong></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Annuel -->
                        <div class="tab-pane fade" id="annuel" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5>Moyenne annuelle</h5>
                                            <h2 class="text-primary">{{ number_format($moyenneAnnuelle ?? 13.6, 2) }}/20</h2>
                                            <p>Total crédits: <strong>{{ $totalCredits ?? 45 }}</strong>/60</p>
                                            <div class="progress mt-3" style="height: 25px;">
                                                <div class="progress-bar bg-success" 
                                                     style="width: {{ ($totalCredits ?? 45)/60*100 }}%">
                                                    {{ round(($totalCredits ?? 45)/60*100) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5>Récapitulatif</h5>
                                            <table class="table table-sm">
                                                <tr>
                                                    <th>Unités validées</th>
                                                    <td>{{ $unitesValidees ?? 12 }}/16</td>
                                                </tr>
                                                <tr>
                                                    <th>Mention</th>
                                                    <td>
                                                        @php
                                                            $moy = $moyenneAnnuelle ?? 13.6;
                                                            $mention = $moy >= 16 ? 'Très bien' : ($moy >= 14 ? 'Bien' : ($moy >= 12 ? 'Assez bien' : ($moy >= 10 ? 'Passable' : 'Insuffisant')));
                                                        @endphp
                                                        <span class="badge bg-{{ $moy >= 10 ? 'success' : 'danger' }}">
                                                            {{ $mention }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Rang</th>
                                                    <td>{{ $rang ?? '12' }}/{{ $promotion ?? 45 }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique d'évolution -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Évolution des notes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('evolutionChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'],
            datasets: [{
                label: 'Moyenne par semestre',
                data: [12.5, 13.2, 12.8, 14.1, 13.5, 14.2],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
@endpush
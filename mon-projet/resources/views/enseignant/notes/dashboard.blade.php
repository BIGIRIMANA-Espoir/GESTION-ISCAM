@extends('layouts.dashboard')

@section('title', 'Dashboard Enseignant')
@section('page-title', 'Mon Tableau de Bord')

@section('menu')
    <a href="{{ route('enseignant.dashboard') }}" class="active">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('enseignant.cours.index') }}">
        <i class="fas fa-book"></i> Mes cours
    </a>
    <a href="{{ route('enseignant.notes.index') }}">
        <i class="fas fa-pen"></i> Saisie des notes
    </a>
    <a href="{{ route('enseignant.etudiants.index') }}">
        <i class="fas fa-user-graduate"></i> Mes étudiants
    </a>
    <a href="{{ route('enseignant.profile') }}">
        <i class="fas fa-user"></i> Mon profil
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête de bienvenue -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                            <i class="fas fa-chalkboard-teacher fa-3x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">Bienvenue, {{ auth()->user()->enseignant->prenom ?? '' }} {{ auth()->user()->enseignant->nom ?? '' }}</h4>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-calendar me-2"></i> {{ now()->format('l d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Mes cours</h6>
                            <h3 class="mb-0">{{ $stats['total_cours'] ?? 4 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-book text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total étudiants</h6>
                            <h3 class="mb-0">{{ $stats['total_etudiants'] ?? 127 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-users text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Notes saisies</h6>
                            <h3 class="mb-0">{{ $stats['notes_saisies'] ?? 450 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-chart-line text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Moyenne générale</h6>
                            <h3 class="mb-0">{{ number_format($stats['moyenne_generale'] ?? 13.5, 2) }}/20</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-star text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes cours et planning -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-book-open text-primary me-2"></i>
                            Mes cours du semestre
                        </h5>
                        <a href="{{ route('enseignant.cours.index') }}" class="btn btn-sm btn-primary">
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(($stats['derniers_cours'] ?? collect())->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cours</th>
                                        <th>Code</th>
                                        <th>Département</th>
                                        <th>Étudiants</th>
                                        <th>Moyenne</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['derniers_cours'] as $cours)
                                    <tr>
                                        <td>
                                            <strong>{{ $cours->nom_cours }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $cours->description }}</small>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $cours->code_cours }}</span></td>
                                        <td>{{ $cours->departement->nom_departement ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $cours->inscriptions->count() }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $moyenne = $cours->notes->avg('note');
                                            @endphp
                                            @if($moyenne)
                                                <span class="badge bg-{{ $moyenne >= 10 ? 'success' : 'danger' }}">
                                                    {{ number_format($moyenne, 2) }}/20
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('enseignant.notes.create', $cours->id) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-pen"></i> Saisir notes
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Exemple statique -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td><strong>Programmation Web</strong><br><small class="text-muted">L3 Informatique</small></td>
                                        <td><span class="badge bg-secondary">INF304</span></td>
                                        <td>45</td>
                                        <td><span class="badge bg-success">14.5/20</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success">
                                                <i class="fas fa-pen"></i> Saisir notes
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Base de Données</strong><br><small class="text-muted">L2 Informatique</small></td>
                                        <td><span class="badge bg-secondary">INF202</span></td>
                                        <td>38</td>
                                        <td><span class="badge bg-success">12.8/20</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success">
                                                <i class="fas fa-pen"></i> Saisir notes
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Réseaux</strong><br><small class="text-muted">L3 Informatique</small></td>
                                        <td><span class="badge bg-secondary">INF305</span></td>
                                        <td>42</td>
                                        <td><span class="badge bg-warning">9.5/20</span></td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-success">
                                                <i class="fas fa-pen"></i> Saisir notes
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Planning et notifications -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt text-success me-2"></i>
                        Planning du jour
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="bg-primary text-white p-2 rounded text-center me-3" style="min-width: 60px;">
                                    <div class="small">08:00</div>
                                    <div class="fw-bold">10:00</div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Programmation Web</h6>
                                    <p class="mb-0 small text-muted">Salle 203 - L3 Info</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="bg-success text-white p-2 rounded text-center me-3" style="min-width: 60px;">
                                    <div class="small">10:00</div>
                                    <div class="fw-bold">12:00</div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Base de Données</h6>
                                    <p class="mb-0 small text-muted">Salle 105 - L2 Info</p>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="bg-info text-white p-2 rounded text-center me-3" style="min-width: 60px;">
                                    <div class="small">14:00</div>
                                    <div class="fw-bold">16:00</div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Réseaux</h6>
                                    <p class="mb-0 small text-muted">Salle TP2 - L3 Info</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bell text-warning me-2"></i>
                        Notifications
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-2">
                        <small class="text-muted">Il y a 5 min</small>
                        <p class="mb-0">Notes validées pour L3 Info</p>
                    </div>
                    <div class="alert alert-info mb-2">
                        <small class="text-muted">Il y a 2 heures</small>
                        <p class="mb-0">Réunion pédagogique demain</p>
                    </div>
                    <div class="alert alert-warning mb-0">
                        <small class="text-muted">Il y a 1 jour</small>
                        <p class="mb-0">Date limite saisie notes: 30/03</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières notes saisies -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-info me-2"></i>
                        Dernières notes saisies
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Matricule</th>
                                    <th>Cours</th>
                                    <th>Note</th>
                                    <th>Session</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jean Ndayishimiye</td>
                                    <td><span class="badge bg-secondary">ISC2024001</span></td>
                                    <td>Programmation Web</td>
                                    <td><span class="badge bg-success">16.5/20</span></td>
                                    <td>Normale</td>
                                    <td>20/02/2026</td>
                                </tr>
                                <tr>
                                    <td>Marie Uwase</td>
                                    <td><span class="badge bg-secondary">ISC2024002</span></td>
                                    <td>Base de Données</td>
                                    <td><span class="badge bg-success">14.0/20</span></td>
                                    <td>Normale</td>
                                    <td>19/02/2026</td>
                                </tr>
                                <tr>
                                    <td>Pierre Nkurunziza</td>
                                    <td><span class="badge bg-secondary">ISC2024003</span></td>
                                    <td>Réseaux</td>
                                    <td><span class="badge bg-danger">8.5/20</span></td>
                                    <td>Normale</td>
                                    <td>18/02/2026</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
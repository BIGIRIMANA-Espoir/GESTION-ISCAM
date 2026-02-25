@extends('layouts.dashboard')

@section('title', 'Dashboard Étudiant')
@section('page-title', 'Mon Espace Étudiant')

@section('menu')
    <a href="{{ route('etudiant.dashboard') }}" class="active">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('etudiant.notes.index') }}">
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
    <!-- En-tête de bienvenue -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 p-3 rounded-circle me-3">
                            <i class="fas fa-user-graduate fa-3x"></i>
                        </div>
                        <div>
                            <h4 class="mb-1">Bienvenue, {{ $etudiant->prenom }} {{ $etudiant->nom }}</h4>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-calendar me-2"></i> 
                                <!-- ✅ DATE DYNAMIQUE COMPLÈTE (jour mois année) -->
                                {{ \Carbon\Carbon::now()->locale('fr')->translatedFormat('j F Y') }}
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
                            <h6 class="text-muted mb-1">Moyenne générale</h6>
                            <h3 class="mb-0">{{ number_format($stats['moyenne_generale'] ?? 0, 2) }}/20</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-star text-primary fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Crédits obtenus</h6>
                            <h3 class="mb-0">{{ $stats['total_credits'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-gem text-success fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Cours suivis</h6>
                            <h3 class="mb-0">{{ $stats['total_cours'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-book text-warning fa-2x"></i>
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
                            <h6 class="text-muted mb-1">Année en cours</h6>
                            <h3 class="mb-0">{{ $anneeCourante }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-calendar text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières notes et statut inscription -->
    <div class="row">
        <div class="col-xl-8 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Dernières notes
                        </h5>
                        <a href="{{ route('etudiant.notes.index') }}" class="btn btn-sm btn-primary">
                            Voir tout
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cours</th>
                                    <th>Code</th>
                                    <th>Note</th>
                                    <th>Session</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['dernieres_notes'] ?? [] as $note)
                                <tr>
                                    <td>{{ $note->cours->nom_cours ?? '' }}</td>
                                    <td><span class="badge bg-secondary">{{ $note->cours->code_cours ?? '' }}</span></td>
                                    <td>
                                        <span class="badge bg-{{ ($note->note ?? 0) >= 10 ? 'success' : 'danger' }}">
                                            {{ number_format($note->note ?? 0, 2) }}/20
                                        </span>
                                    </td>
                                    <td>{{ $note->session ?? 'Normale' }}</td>
                                    <td>{{ $note->created_at ? $note->created_at->format('d/m/Y') : '' }}</td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Aucune note disponible
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-pen-to-square text-success me-2"></i>
                        Statut inscription
                    </h5>
                </div>
                <div class="card-body">
                    @if($stats['inscription_actuelle'] ?? false)
                        @if($stats['inscription_actuelle']->statut == 'validee')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Inscription validée</strong>
                                <p class="mb-0 small mt-2">Année {{ $anneeCourante }}</p>
                            </div>
                        @elseif($stats['inscription_actuelle']->statut == 'en_attente')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Inscription en attente</strong>
                                <p class="mb-0 small mt-2">Votre demande est en cours de traitement</p>
                                <a href="{{ route('etudiant.inscriptions.show', $stats['inscription_actuelle']->id) }}" 
                                   class="btn btn-sm btn-warning mt-2">
                                    Voir détails
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Non inscrit</strong>
                            <p class="mb-0 small mt-2">
                                Vous devez vous inscrire pour l'année {{ $anneeCourante }}
                            </p>
                            <a href="{{ route('etudiant.inscriptions.create') }}" class="btn btn-sm btn-primary mt-2">
                                S'inscrire maintenant
                            </a>
                        </div>
                    @endif

                    <hr>

                    <h6 class="mb-3">Prochains événements</h6>
                    <div class="list-group list-group-flush">
                        @foreach($stats['prochains_evenements'] ?? [] as $event)
                        <div class="list-group-item px-0">
                            <div class="d-flex">
                                <div class="bg-primary text-white p-2 rounded text-center me-3" style="min-width: 60px;">
                                    @php
                                        $parts = explode(' ', $event['date']);
                                        $jour = $parts[0] ?? '';
                                        $mois = $parts[1] ?? '';
                                    @endphp
                                    <div class="small">{{ $jour }}</div>
                                    <div class="fw-bold">{{ $mois }}</div>
                                </div>
                                <div>
                                    <h6 class="mb-1">{{ $event['evenement'] }}</h6>
                                    <p class="mb-0 small text-muted">{{ $event['date'] }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
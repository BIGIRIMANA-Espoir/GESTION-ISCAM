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
                            <h4 class="mb-1">Bienvenue, {{ Auth::user()->enseignant->prenom }} {{ Auth::user()->enseignant->nom }}</h4>
                            <p class="mb-0 opacity-75">
                                <i class="fas fa-calendar me-2"></i> {{ $dateActuelle }}
                            </p>
                            <p class="mb-0 opacity-75 mt-1">
                                <i class="fas fa-calendar-alt me-2"></i> Année académique: {{ $anneeCourante }}
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
                            <h3 class="mb-0">{{ $stats['total_cours'] ?? 0 }}</h3>
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
                            <h3 class="mb-0">{{ $stats['total_etudiants'] ?? 0 }}</h3>
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
                            <h3 class="mb-0">{{ $stats['notes_saisies'] ?? 0 }}</h3>
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
                            <h3 class="mb-0">{{ number_format($stats['moyenne_generale'] ?? 0, 2) }}/20</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-star text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mes cours -->
    <div class="row mb-4">
        <div class="col-xl-12">
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
                    @if($cours->count() > 0)
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
                                    @foreach($cours as $c)
                                    <tr>
                                        <td>
                                            <strong>{{ $c->nom_cours }}</strong>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $c->code_cours }}</span></td>
                                        <td>{{ $c->departement->nom_departement ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $c->inscriptions?->count() ?? 0 }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $moyenne = $c->notes?->avg('note') ?? 0;
                                            @endphp
                                            @if($moyenne > 0)
                                                <span class="badge bg-{{ $moyenne >= 10 ? 'success' : 'danger' }}">
                                                    {{ number_format($moyenne, 2) }}/20
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('enseignant.notes.create', $c->id) }}" 
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
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun cours assigné pour le moment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
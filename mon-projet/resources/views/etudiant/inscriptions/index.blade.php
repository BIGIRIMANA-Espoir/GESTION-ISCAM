@extends('layouts.dashboard')

@section('title', 'Mes Inscriptions')
@section('page-title', 'Historique des Inscriptions')

@section('menu')
    <a href="{{ route('etudiant.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('etudiant.notes.index') }}">
        <i class="fas fa-chart-line"></i> Mes notes
    </a>
    <a href="{{ route('etudiant.inscriptions.index') }}" class="active">
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
                                <i class="fas fa-pen-to-square me-2"></i>
                                Mes inscriptions académiques
                            </h4>
                            <p class="mb-0 opacity-75">Historique de vos inscriptions à l'ISCAM</p>
                        </div>
                        <a href="{{ route('etudiant.inscriptions.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i> Nouvelle demande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statut de l'année en cours -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-{{ $inscriptionActuelle ? ($inscriptionActuelle->statut == 'validee' ? 'success' : ($inscriptionActuelle->statut == 'en_attente' ? 'warning' : 'danger')) : 'secondary' }} bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-calendar-check fa-2x text-{{ $inscriptionActuelle ? ($inscriptionActuelle->statut == 'validee' ? 'success' : ($inscriptionActuelle->statut == 'en_attente' ? 'warning' : 'danger')) : 'secondary' }}"></i>
                        </div>
                        <div>
                            <h5 class="mb-1">Année académique {{ $anneeCouranteObj->annee ?? date('Y').'-'.(date('Y')+1) }}</h5>
                            
                            @if($inscriptionActuelle)
                                @if($inscriptionActuelle->statut == 'validee')
                                    <span class="badge bg-success">Inscription validée</span>
                                    <p class="text-muted small mt-2 mb-0">Vous êtes officiellement inscrit pour cette année</p>
                                @elseif($inscriptionActuelle->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente de validation</span>
                                    <p class="text-muted small mt-2 mb-0">Votre demande est en cours de traitement par l'administration</p>
                                @else
                                    <span class="badge bg-danger">Demande rejetée</span>
                                    <p class="text-muted small mt-2 mb-0">Votre demande a été rejetée. Contactez l'administration.</p>
                                @endif
                            @else
                                <span class="badge bg-secondary">Non inscrit</span>
                                <p class="text-muted small mt-2 mb-0">Vous n'avez pas encore fait de demande pour cette année</p>
                                <a href="{{ route('etudiant.inscriptions.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="fas fa-plus me-1"></i> Faire une demande
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des inscriptions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-primary me-2"></i>
                        Historique des inscriptions
                    </h5>
                </div>
                <div class="card-body">
                    @if($inscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Année académique</th>
                                        <th>Date demande</th>
                                        <th>Date validation</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inscriptions as $inscription)
                                    <tr>
                                        <td>
                                            <strong>{{ $inscription->anneeAcademique->annee ?? 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $inscription->created_at ? $inscription->created_at->format('d/m/Y') : 'N/A' }}</td>
                                        <td>
                                            @if($inscription->statut == 'validee')
                                                {{ $inscription->updated_at ? $inscription->updated_at->format('d/m/Y') : 'N/A' }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($inscription->statut == 'validee')
                                                <span class="badge bg-success">Validée</span>
                                            @elseif($inscription->statut == 'en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @else
                                                <span class="badge bg-danger">Rejetée</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('etudiant.inscriptions.show', $inscription->id) }}" 
                                               class="btn btn-sm btn-info"
                                               title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-pen-to-square fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune inscription trouvée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
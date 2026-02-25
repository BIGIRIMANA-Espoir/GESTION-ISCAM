@extends('layouts.dashboard')

@section('title', 'Détail Inscription')
@section('page-title', 'Ma Demande d\'Inscription')

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
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-pen-to-square me-2"></i>
                                Demande d'inscription #{{ $inscription->id }}
                            </h4>
                            <p class="mb-0 opacity-75">
                                {{ $inscription->anneeAcademique->annee ?? 'N/A' }}
                            </p>
                        </div>
                        <a href="{{ route('etudiant.inscriptions.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Détails de l'inscription
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($inscription->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($inscription->statut == 'validee')
                                    <span class="badge bg-success">Validée</span>
                                @else
                                    <span class="badge bg-danger">Rejetée</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Année académique</th>
                            <td><strong>{{ $inscription->anneeAcademique->annee ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Date de demande</th>
                            <td>{{ $inscription->created_at ? $inscription->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        @if($inscription->statut != 'en_attente')
                        <tr>
                            <th>Date de traitement</th>
                            <td>{{ $inscription->updated_at ? $inscription->updated_at->format('d/m/Y H:i') : 'N/A' }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-graduation-cap text-success me-2"></i>
                        Mes informations
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Matricule</th>
                            <td><span class="badge bg-secondary">{{ Auth::user()->etudiant->matricule ?? 'N/A' }}</span></td>
                        </tr>
                        <tr>
                            <th>Nom complet</th>
                            <td>{{ Auth::user()->etudiant->prenom ?? '' }} {{ Auth::user()->etudiant->nom ?? '' }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ Auth::user()->etudiant->email ?? '' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($inscription->statut == 'en_attente')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-body text-center">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Vous pouvez annuler cette demande si vous le souhaitez.
                    </p>
                    <form action="{{ route('etudiant.inscriptions.destroy', $inscription->id) }}" method="POST" 
                          onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fas fa-times me-2"></i> Annuler la demande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
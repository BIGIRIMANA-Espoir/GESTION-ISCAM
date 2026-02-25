@extends('layouts.dashboard')

@section('title', 'Détail Étudiant')
@section('page-title', 'Fiche Étudiant')

@section('menu')
    <a href="{{ route('enseignant.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('enseignant.cours.index') }}">
        <i class="fas fa-book"></i> Mes cours
    </a>
    <a href="{{ route('enseignant.notes.index') }}">
        <i class="fas fa-pen"></i> Saisie des notes
    </a>
    <a href="{{ route('enseignant.etudiants.index') }}" class="active">
        <i class="fas fa-user-graduate"></i> Mes étudiants
    </a>
    <a href="{{ route('enseignant.profile') }}">
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
                                <i class="fas fa-user-graduate me-2"></i>
                                {{ $etudiant->prenom }} {{ $etudiant->nom }}
                            </h4>
                            <p class="mb-0 opacity-75">
                                Matricule: {{ $etudiant->matricule }}
                            </p>
                        </div>
                        <a href="{{ route('enseignant.etudiants.index') }}" class="btn btn-light">
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
                        Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>Matricule</th>
                            <td><span class="badge bg-secondary">{{ $etudiant->matricule }}</span></td>
                        </tr>
                        <tr>
                            <th>Nom complet</th>
                            <td>{{ $etudiant->prenom }} {{ $etudiant->nom }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $etudiant->email }}</td>
                        </tr>
                        <tr>
                            <th>Téléphone</th>
                            <td>{{ $etudiant->telephone ?? 'Non renseigné' }}</td>
                        </tr>
                        <tr>
                            <th>Date naissance</th>
                            <td>{{ $etudiant->date_naissance->format('d/m/Y') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-success me-2"></i>
                        Notes de l'étudiant
                    </h5>
                </div>
                <div class="card-body">
                    @if($etudiant->notes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Cours</th>
                                        <th>Note</th>
                                        <th>Session</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiant->notes as $note)
                                    <tr>
                                        <td>{{ $note->cours->nom_cours }}</td>
                                        <td>
                                            <span class="badge bg-{{ $note->note >= 10 ? 'success' : 'danger' }}">
                                                {{ $note->note }}/20
                                            </span>
                                        </td>
                                        <td>{{ $note->session }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucune note disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
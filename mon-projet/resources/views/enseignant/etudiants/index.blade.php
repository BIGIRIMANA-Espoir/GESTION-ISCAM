@extends('layouts.dashboard')

@section('title', 'Mes Étudiants')
@section('page-title', 'Liste des Étudiants')

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
    <!-- En-tête avec recherche -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="mb-0">
                        <i class="fas fa-user-graduate me-2"></i>
                        Mes étudiants
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Recherche -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('enseignant.etudiants.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Rechercher par nom, prénom ou matricule..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            @if(request('search'))
                                <a href="{{ route('enseignant.etudiants.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Effacer
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Liste des étudiants
                        @if(request('search'))
                            <small class="text-muted ms-2">
                                Résultats pour "{{ request('search') }}"
                            </small>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($etudiants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Matricule</th>
                                        <th>Nom & Prénom</th>
                                        <th>Email</th>
                                        <th>Téléphone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiants as $etudiant)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $etudiant->matricule }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $etudiant->nom }}</strong> {{ $etudiant->prenom }}
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $etudiant->email }}">
                                                <small>{{ $etudiant->email }}</small>
                                            </a>
                                        </td>
                                        <td>{{ $etudiant->telephone ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('enseignant.etudiants.show', $etudiant->id) }}" 
                                               class="btn btn-sm btn-info"
                                               title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination (UNIQUEMENT si pas de recherche) -->
                        @if(!request('search'))
                        <div class="d-flex justify-content-center mt-3">
                            {{ $etudiants->appends(request()->query())->links() }}
                        </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h5>Aucun étudiant trouvé</h5>
                            @if(request('search'))
                                <p class="text-muted">Aucun résultat pour "{{ request('search') }}"</p>
                                <a href="{{ route('enseignant.etudiants.index') }}" class="btn btn-primary">
                                    Voir tous les étudiants
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
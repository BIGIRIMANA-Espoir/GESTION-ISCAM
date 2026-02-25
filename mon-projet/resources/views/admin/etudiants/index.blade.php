@extends('layouts.dashboard')

@section('title', 'Gestion des Étudiants')
@section('page-title', 'Liste des Étudiants')

@section('menu')
    <a href="{{ route('admin.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('admin.etudiants.index') }}" class="active">
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
    <!-- En-tête avec recherche et ajout -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('admin.etudiants.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Rechercher par nom, prénom ou matricule..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.etudiants.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times"></i> Effacer
                    </a>
                @endif
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.etudiants.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nouvel Étudiant
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Étudiants</h5>
                    <h2>{{ $totalEtudiants ?? 0 }}</h2>  <!-- ← CORRIGÉ -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Inscrits cette année</h5>
                    <h2>{{ $inscriptionsAnnee ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Moyenne générale</h5>
                    <h2>{{ $moyenneGenerale ?? 'N/A' }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Nouveaux (30j)</h5>
                    <h2>{{ $nouveauxEtudiants ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des étudiants -->
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
                                <th>Sexe</th>
                                <th>Inscriptions</th>
                                <th>Moyenne</th>
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
                                <td>{{ $etudiant->email }}</td>
                                <td>{{ $etudiant->telephone ?? 'N/A' }}</td>
                                <td>
                                    @if($etudiant->sexe == 'M')
                                        <i class="fas fa-mars text-primary"></i> Masculin
                                    @else
                                        <i class="fas fa-venus text-danger"></i> Féminin
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $etudiant->inscriptions?->count() ?? 0 }}  <!-- ← SÉCURISÉ -->
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $moyenne = $etudiant->notes?->avg('note') ?? 0;
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
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.etudiants.show', $etudiant->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.etudiants.edit', $etudiant->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                title="Supprimer"
                                                onclick="confirmDelete({{ $etudiant->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $etudiant->id }}" 
                                          action="{{ route('admin.etudiants.destroy', $etudiant->id) }}" 
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (simplifiée) -->
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
                        <p>Aucun résultat pour "{{ request('search') }}"</p>
                        <a href="{{ route('admin.etudiants.index') }}" class="btn btn-primary">
                            Voir tous les étudiants
                        </a>
                    @else
                        <a href="{{ route('admin.etudiants.create') }}" class="btn btn-primary">
                            Ajouter un étudiant
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ? Cette action est irréversible.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
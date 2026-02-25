@extends('layouts.dashboard')

@section('title', 'Gestion des Enseignants')
@section('page-title', 'Liste des Enseignants')

@section('menu')
    <a href="{{ route('admin.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('admin.etudiants.index') }}">
        <i class="fas fa-users"></i> Étudiants
    </a>
    <a href="{{ route('admin.enseignants.index') }}" class="active">
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
            <form action="{{ route('admin.enseignants.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Rechercher par nom, prénom, matricule ou email..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.enseignants.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times"></i> Effacer
                    </a>
                @endif
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.enseignants.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nouvel Enseignant
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Enseignants</h5>
                    <h2>{{ $totalEnseignants ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Total Cours</h5>
                    <h2>{{ $totalCours ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Moyenne/Classe</h5>
                    <h2>{{ $moyenneGlobale ?? 'N/A' }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Départements</h5>
                    <h2>{{ $totalDepartements ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des enseignants -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Liste des enseignants
                @if(request('search'))
                    <small class="text-muted ms-2">
                        Résultats pour "{{ request('search') }}"
                    </small>
                @endif
            </h5>
        </div>
        <div class="card-body">
            @if($enseignants->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom & Prénom</th>
                                <th>Grade</th>
                                <th>Spécialité</th>
                                <th>Département</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Cours</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enseignants as $enseignant)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $enseignant->matricule }}</span>
                                </td>
                                <td>
                                    <strong>{{ $enseignant->nom }}</strong> {{ $enseignant->prenom }}
                                </td>
                                <td>{{ $enseignant->grade }}</td>
                                <td>{{ $enseignant->specialite }}</td>
                                <td>
                                    @if($enseignant->departement)
                                        <span class="badge bg-info">
                                            {{ $enseignant->departement->nom_departement }}
                                        </span>
                                    @else
                                        <span class="text-muted">Non affecté</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="mailto:{{ $enseignant->email }}">
                                        <small>{{ $enseignant->email }}</small>
                                    </a>
                                </td>
                                <td>{{ $enseignant->telephone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $enseignant->cours?->count() ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.enseignants.show', $enseignant->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.enseignants.edit', $enseignant->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                title="Supprimer"
                                                onclick="confirmDelete({{ $enseignant->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $enseignant->id }}" 
                                          action="{{ route('admin.enseignants.destroy', $enseignant->id) }}" 
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

                <!-- Pagination (simplifiée - SANS TEXTE) -->
                @if(!request('search'))
                <div class="d-flex justify-content-center mt-3">
                    {{ $enseignants->appends(request()->query())->links() }}
                </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chalkboard-teacher fa-4x text-muted mb-3"></i>
                    <h5>Aucun enseignant trouvé</h5>
                    @if(request('search'))
                        <p>Aucun résultat pour "{{ request('search') }}"</p>
                        <a href="{{ route('admin.enseignants.index') }}" class="btn btn-primary">
                            Voir tous les enseignants
                        </a>
                    @else
                        <a href="{{ route('admin.enseignants.create') }}" class="btn btn-primary">
                            Ajouter un enseignant
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
        if (confirm('Êtes-vous sûr de vouloir supprimer cet enseignant ? Cette action est irréversible.')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
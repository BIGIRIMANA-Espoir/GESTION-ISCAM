@extends('layouts.dashboard')

@section('title', 'Gestion des Cours')
@section('page-title', 'Liste des Cours')

@section('menu')
    <a href="{{ route('admin.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('admin.etudiants.index') }}">
        <i class="fas fa-users"></i> Étudiants
    </a>
    <a href="{{ route('admin.enseignants.index') }}">
        <i class="fas fa-chalkboard-teacher"></i> Enseignants
    </a>
    <a href="{{ route('admin.cours.index') }}" class="active">
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
            <form action="{{ route('admin.cours.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Rechercher par code, nom ou département..." 
                       value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Rechercher
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary ms-2">
                        <i class="fas fa-times"></i> Effacer
                    </a>
                @endif
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.cours.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Nouveau Cours
            </a>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total Cours</h5>
                    <h2>{{ $cours->total() ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Crédits totaux</h5>
                    <h2>{{ $totalCredits ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5>Moyenne générale</h5>
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

    <!-- Filtres par département -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.cours.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <select name="departement_id" class="form-select">
                                <option value="">Tous les départements</option>
                                @foreach($departements ?? [] as $id => $nom)
                                    <option value="{{ $id }}" 
                                        {{ request('departement_id') == $id ? 'selected' : '' }}>
                                        {{ $nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des cours -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Liste des cours
                @if(request('search'))
                    <small class="text-muted ms-2">
                        Résultats pour "{{ request('search') }}"
                    </small>
                @endif
                @if(request('departement_id'))
                    <small class="text-muted ms-2">
                        Filtre par département appliqué
                    </small>
                @endif
            </h5>
        </div>
        <div class="card-body">
            @if($cours->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Nom du cours</th>
                                <th>Département</th>
                                <th>Enseignant</th>
                                <th>Crédits</th>
                                <th>Étudiants</th>
                                <th>Moyenne</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cours as $c)
                            <tr>
                                <td>
                                    <span class="badge bg-secondary">{{ $c->code_cours }}</span>
                                </td>
                                <td>
                                    <strong>{{ $c->nom_cours }}</strong>
                                    @if($c->description)
                                        <br><small class="text-muted">{{ Str::limit($c->description, 30) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($c->departement)
                                        <span class="badge bg-info">
                                            {{ $c->departement->nom_departement }}
                                        </span>
                                    @else
                                        <span class="text-muted">Non affecté</span>
                                    @endif
                                </td>
                                <td>
                                    @if($c->enseignant)
                                        {{ $c->enseignant->prenom }} {{ $c->enseignant->nom }}
                                    @else
                                        <span class="text-muted">Non assigné</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $c->credits }}</span>
                                </td>
                                <td class="text-center">
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
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.cours.show', $c->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.cours.edit', $c->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.notes.index', ['cours_id' => $c->id]) }}" 
                                           class="btn btn-sm btn-primary"
                                           title="Voir notes">
                                            <i class="fas fa-chart-line"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                title="Supprimer"
                                                onclick="confirmDelete({{ $c->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $c->id }}" 
                                          action="{{ route('admin.cours.destroy', $c->id) }}" 
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
                <div class="d-flex justify-content-center mt-3">
                    {{ $cours->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                    <h5>Aucun cours trouvé</h5>
                    @if(request('search') || request('departement_id'))
                        <p>Aucun résultat pour les critères sélectionnés</p>
                        <a href="{{ route('admin.cours.index') }}" class="btn btn-primary">
                            Voir tous les cours
                        </a>
                    @else
                        <a href="{{ route('admin.cours.create') }}" class="btn btn-primary">
                            Ajouter un cours
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
        if (confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
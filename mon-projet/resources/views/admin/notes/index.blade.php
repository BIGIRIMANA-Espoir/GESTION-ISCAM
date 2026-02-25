@extends('layouts.dashboard')

@section('title', 'Gestion des Notes')
@section('page-title', 'Liste des Notes')

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
    <a href="{{ route('admin.cours.index') }}">
        <i class="fas fa-book"></i> Cours
    </a>
    <a href="{{ route('admin.inscriptions.index') }}">
        <i class="fas fa-pen-to-square"></i> Inscriptions
    </a>
    <a href="{{ route('admin.notes.index') }}" class="active">
        <i class="fas fa-chart-line"></i> Notes
    </a>
    <a href="{{ route('admin.statistiques.index') }}">
        <i class="fas fa-chart-bar"></i> Statistiques
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.notes.index') }}" method="GET" class="row">
                        <div class="col-md-3 mb-2">
                            <select name="cours_id" class="form-select">
                                <option value="">Tous les cours</option>
                                @foreach($coursList as $c)
                                    <option value="{{ $c->id }}" 
                                        {{ request('cours_id') == $c->id ? 'selected' : '' }}>
                                        {{ $c->code_cours }} - {{ $c->nom_cours }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select name="session" class="form-select">
                                <option value="">Toutes sessions</option>
                                <option value="normale" {{ request('session') == 'normale' ? 'selected' : '' }}>Normale</option>
                                <option value="rattrapage" {{ request('session') == 'rattrapage' ? 'selected' : '' }}>Rattrapage</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Rechercher étudiant..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 mb-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $statistiques['total_notes'] ?? 0 }}</h3>
                    <small>Total notes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ number_format($statistiques['moyenne_globale'] ?? 0, 2) }}/20</h3>
                    <small>Moyenne générale</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $statistiques['notes_normales'] ?? 0 }}</h3>
                    <small>Session normale</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3>{{ $statistiques['notes_rattrapage'] ?? 0 }}</h3>
                    <small>Rattrapage</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des notes -->
    <div class="card">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Liste des notes
                </h5>
                <a href="{{ route('admin.notes.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Nouvelle note
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($notes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Matricule</th>
                                <th>Cours</th>
                                <th>Note</th>
                                <th>Session</th>
                                <th>Année</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notes as $note)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.etudiants.show', $note->etudiant_id) }}">
                                        {{ $note->etudiant->prenom ?? '' }} {{ $note->etudiant->nom ?? '' }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $note->etudiant->matricule ?? '' }}</span>
                                </td>
                                <td>
                                    <strong>{{ $note->cours->nom_cours ?? '' }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ ($note->note ?? 0) >= 10 ? 'success' : 'danger' }} fs-6">
                                        {{ $note->note ?? 0 }}/20
                                    </span>
                                </td>
                                <td>
                                    @if(($note->session ?? '') == 'normale')
                                        <span class="badge bg-info">Normale</span>
                                    @elseif(($note->session ?? '') == 'rattrapage')
                                        <span class="badge bg-warning">Rattrapage</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>{{ $note->annee_academique ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.notes.show', $note->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir les détails de cette note">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.notes.edit', $note->id) }}" 
                                           class="btn btn-sm btn-warning"
                                           title="Modifier cette note">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                title="Supprimer cette note"
                                                onclick="confirmDelete({{ $note->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $note->id }}" 
                                          action="{{ route('admin.notes.destroy', $note->id) }}" 
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
                    {{ $notes->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                    <h5>Aucune note trouvée</h5>
                    <a href="{{ route('admin.notes.create') }}" class="btn btn-primary">
                        Saisir des notes
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette note ?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>
@endpush
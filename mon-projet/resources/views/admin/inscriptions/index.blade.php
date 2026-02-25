@extends('layouts.dashboard')

@section('title', 'Gestion des Inscriptions')
@section('page-title', 'Liste des Inscriptions')

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
    <a href="{{ route('admin.inscriptions.index') }}" class="active">
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
    <!-- En-tête avec titre et bouton d'ajout -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-pen-to-square text-primary me-2"></i>
                    Gestion des Inscriptions
                </h4>
                <a href="{{ route('admin.inscriptions.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i> Nouvelle Inscription
                </a>
            </div>
        </div>
    </div>

    <!-- En-tête avec filtres -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.inscriptions.index') }}" method="GET" class="row">
                        <div class="col-md-3 mb-2">
                            <select name="statut" class="form-select">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>
                                    En attente
                                </option>
                                <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>
                                    Validée
                                </option>
                                <option value="rejetee" {{ request('statut') == 'rejetee' ? 'selected' : '' }}>
                                    Rejetée
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select name="annee_id" class="form-select">
                                <option value="">Toutes les années</option>
                                @foreach($annees as $id => $annee)
                                    <option value="{{ $id }}" {{ request('annee_id') == $id ? 'selected' : '' }}>
                                        {{ $annee }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Rechercher par étudiant..." 
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

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Total</h5>
                    <h2>{{ $stats['total'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>En attente</h5>
                    <h2>{{ $stats['en_attente'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Validées</h5>
                    <h2>{{ $stats['validees'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Rejetées</h5>
                    <h2>{{ $stats['rejetees'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des inscriptions -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2 text-primary"></i>
                Liste des inscriptions
            </h5>
        </div>
        <div class="card-body">
            @if($inscriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Étudiant</th>
                                <th>Année académique</th>
                                <th>Date inscription</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($inscriptions as $inscription)
                            <tr>
                                <td>{{ $inscription->id }}</td>
                                <td>
                                    <a href="{{ route('admin.etudiants.show', $inscription->etudiant_id) }}">
                                        <strong>{{ $inscription->etudiant->prenom ?? '' }} {{ $inscription->etudiant->nom ?? '' }}</strong>
                                    </a>
                                    <br>
                                    <small class="text-muted">{{ $inscription->etudiant->matricule ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $inscription->anneeAcademique->annee ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $inscription->date_inscription->format('d/m/Y') }}</td>
                                <td>
                                    @if($inscription->statut == 'en_attente')
                                        <span class="badge bg-warning">En attente</span>
                                    @elseif($inscription->statut == 'validee')
                                        <span class="badge bg-success">Validée</span>
                                    @else
                                        <span class="badge bg-danger">Rejetée</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- Bouton Voir détails avec tooltip -->
                                        <a href="{{ route('admin.inscriptions.show', $inscription->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir les détails complets de cette inscription">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($inscription->statut == 'en_attente')
                                            <!-- Bouton Valider avec tooltip -->
                                            <form action="{{ route('admin.inscriptions.approuver', $inscription->id) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success" 
                                                        title="Valider cette inscription (l'étudiant recevra une notification)"
                                                        onclick="return confirm('Valider cette inscription?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <!-- Bouton Rejeter avec tooltip -->
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    title="Rejeter cette inscription avec une raison"
                                                    onclick="showRejectModal({{ $inscription->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination (sans le texte, seulement les liens) -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $inscriptions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-pen-to-square fa-4x text-muted mb-3"></i>
                    <h5>Aucune inscription trouvée</h5>
                    <p class="text-muted mb-3">
                        Commencez par créer une nouvelle inscription en cliquant sur le bouton 
                        <strong>"Nouvelle Inscription"</strong> en haut de la page.
                    </p>
                    <a href="{{ route('admin.inscriptions.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Créer une inscription
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Rejeter l'inscription
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="rejectForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Veuillez indiquer la raison du rejet :</p>
                    <textarea name="reason" class="form-control" rows="3" 
                              placeholder="Raison du rejet..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i> Rejeter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showRejectModal(id) {
        const form = document.getElementById('rejectForm');
        form.action = '/admin/inscriptions/' + id + '/reject';
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }
</script>
@endpush
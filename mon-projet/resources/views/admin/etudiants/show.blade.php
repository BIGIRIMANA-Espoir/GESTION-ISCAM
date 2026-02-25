@extends('layouts.dashboard')

@section('title', 'Détails Étudiant')
@section('page-title', 'Fiche Étudiant')

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
    <!-- En-tête avec boutons d'action -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-user-graduate text-primary me-2"></i>
                                {{ $etudiant->prenom }} {{ $etudiant->nom }}
                            </h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-secondary">{{ $etudiant->matricule }}</span>
                                <span class="ms-2">
                                    <i class="fas fa-envelope me-1"></i> {{ $etudiant->email }}
                                </span>
                                @if($etudiant->telephone)
                                <span class="ms-2">
                                    <i class="fas fa-phone me-1"></i> {{ $etudiant->telephone }}
                                </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.etudiants.edit', $etudiant->id) }}" 
                               class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <a href="{{ route('admin.etudiants.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations personnelles -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-id-card text-primary me-2"></i>
                        Informations personnelles
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Matricule</th>
                            <td><strong>{{ $etudiant->matricule }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nom complet</th>
                            <td>{{ $etudiant->prenom }} {{ $etudiant->nom }}</td>
                        </tr>
                        <tr>
                            <th>Date naissance</th>
                            <td>{{ $etudiant->date_naissance->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Lieu naissance</th>
                            <td>{{ $etudiant->lieu_naissance }}</td>
                        </tr>
                        <tr>
                            <th>Sexe</th>
                            <td>
                                @if($etudiant->sexe == 'M')
                                    <i class="fas fa-mars text-primary"></i> Masculin
                                @else
                                    <i class="fas fa-venus text-danger"></i> Féminin
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Coordonnées -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-address-card text-success me-2"></i>
                        Coordonnées
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Email</th>
                            <td>
                                <a href="mailto:{{ $etudiant->email }}">{{ $etudiant->email }}</a>
                            </td>
                        </tr>
                        @if($etudiant->telephone)
                        <tr>
                            <th>Téléphone</th>
                            <td>
                                <a href="tel:{{ $etudiant->telephone }}">{{ $etudiant->telephone }}</a>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <th>Adresse</th>
                            <td>{{ $etudiant->adresse }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-info me-2"></i>
                        Aperçu académique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3>{{ $etudiant->inscriptions->count() }}</h3>
                                <small class="text-muted">Inscriptions</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3>{{ $etudiant->notes->count() }}</h3>
                                <small class="text-muted">Notes</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-primary text-white rounded">
                                <h2>{{ number_format($statistiques['moyenne_generale'] ?? 0, 2) }}/20</h2>
                                <small>Moyenne générale</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des inscriptions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-pen-to-square text-warning me-2"></i>
                        Historique des inscriptions
                    </h5>
                </div>
                <div class="card-body">
                    @if($etudiant->inscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Année académique</th>
                                        <th>Date d'inscription</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiant->inscriptions as $inscription)
                                    <tr>
                                        <td>
                                            <strong>{{ $inscription->anneeAcademique->annee }}</strong>
                                        </td>
                                        <td>{{ $inscription->date_inscription->format('d/m/Y') }}</td>
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
                                            <a href="{{ route('admin.inscriptions.show', $inscription->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune inscription trouvée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Relevé de notes -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-danger me-2"></i>
                        Relevé de notes
                    </h5>
                </div>
                <div class="card-body">
                    @if($etudiant->notes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cours</th>
                                        <th>Code</th>
                                        <th>Crédits</th>
                                        <th>Note</th>
                                        <th>Session</th>
                                        <th>Année</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($etudiant->notes as $note)
                                    <tr>
                                        <td>{{ $note->cours->nom_cours }}</td>
                                        <td>{{ $note->cours->code_cours }}</td>
                                        <td>{{ $note->cours->credits }}</td>
                                        <td>
                                            <span class="badge bg-{{ $note->note >= 10 ? 'success' : 'danger' }} fs-6">
                                                {{ $note->note }}/20
                                            </span>
                                        </td>
                                        <td>
                                            @if($note->session == 'normale')
                                                <span class="badge bg-info">Normale</span>
                                            @else
                                                <span class="badge bg-warning">Rattrapage</span>
                                            @endif
                                        </td>
                                        <td>{{ $note->annee_academique }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total:</th>
                                        <th>{{ number_format($statistiques['moyenne_generale'] ?? 0, 2) }}/20</th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        <!-- Statistiques détaillées -->
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $statistiques['reussite'] ?? 0 }}</h5>
                                        <small>Notes de réussite (≥10)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $statistiques['echec'] ?? 0 }}</h5>
                                        <small>Notes d'échec (<10)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5>{{ $statistiques['total_credits'] ?? 0 }}</h5>
                                        <small>Crédits obtenus</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-muted mb-0">Aucune note disponible</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression (optionnel) -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirmer la suppression
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer l'étudiant <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong> ?</p>
                    <p class="text-danger mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Cette action est irréversible et supprimera également toutes ses inscriptions et notes.
                    </p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.etudiants.destroy', $etudiant->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fonction pour ouvrir le modal de suppression
    function confirmDelete() {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
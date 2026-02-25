@extends('layouts.dashboard')

@section('title', 'Détails Enseignant')
@section('page-title', 'Fiche Enseignant')

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
    <!-- En-tête avec boutons d'action -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                                {{ $enseignant->prenom }} {{ $enseignant->nom }}
                            </h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-secondary">{{ $enseignant->matricule }}</span>
                                <span class="ms-2">
                                    <i class="fas fa-tag me-1"></i> {{ $enseignant->grade }}
                                </span>
                                <span class="ms-2">
                                    <i class="fas fa-envelope me-1"></i> {{ $enseignant->email }}
                                </span>
                                @if($enseignant->telephone)
                                <span class="ms-2">
                                    <i class="fas fa-phone me-1"></i> {{ $enseignant->telephone }}
                                </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('admin.enseignants.edit', $enseignant->id) }}" 
                               class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <a href="{{ route('admin.enseignants.index') }}" 
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
                            <td><strong>{{ $enseignant->matricule }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nom complet</th>
                            <td>{{ $enseignant->prenom }} {{ $enseignant->nom }}</td>
                        </tr>
                        <tr>
                            <th>Grade</th>
                            <td><span class="badge bg-info">{{ $enseignant->grade }}</span></td>
                        </tr>
                        <tr>
                            <th>Spécialité</th>
                            <td>{{ $enseignant->specialite }}</td>
                        </tr>
                        <tr>
                            <th>Département</th>
                            <td>
                                @if($enseignant->departement)
                                    <span class="badge bg-success">
                                        {{ $enseignant->departement->nom_departement }}
                                    </span>
                                @else
                                    <span class="text-muted">Non affecté</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($enseignant->disponible ?? true)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Disponible
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Indisponible
                                    </span>
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
                                <a href="mailto:{{ $enseignant->email }}">{{ $enseignant->email }}</a>
                            </td>
                        </tr>
                        @if($enseignant->telephone)
                        <tr>
                            <th>Téléphone</th>
                            <td>
                                <a href="tel:{{ $enseignant->telephone }}">{{ $enseignant->telephone }}</a>
                            </td>
                        </tr>
                        @endif
                        @if($enseignant->observations)
                        <tr>
                            <th>Observations</th>
                            <td>
                                <small>{{ $enseignant->observations }}</small>
                            </td>
                        </tr>
                        @endif
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
                                <h3>{{ $enseignant->cours->count() }}</h3>
                                <small class="text-muted">Cours dispensés</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-light rounded">
                                <h3>{{ $totalEtudiants ?? 0 }}</h3>
                                <small class="text-muted">Étudiants</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-primary text-white rounded">
                                <h2>{{ $moyenneClasse ?? 'N/A' }}/20</h2>
                                <small>Moyenne des cours</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des cours dispensés -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-book text-warning me-2"></i>
                            Cours dispensés
                        </h5>
                        <a href="{{ route('admin.cours.create', ['enseignant_id' => $enseignant->id]) }}" 
                           class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Ajouter un cours
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($enseignant->cours->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Nom du cours</th>
                                        <th>Département</th>
                                        <th>Crédits</th>
                                        <th>Étudiants</th>
                                        <th>Moyenne</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enseignant->cours as $cours)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">{{ $cours->code_cours }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $cours->nom_cours }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $cours->description ?? '' }}</small>
                                        </td>
                                        <td>
                                            @if($cours->departement)
                                                {{ $cours->departement->nom_departement }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $cours->credits }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $cours->inscriptions->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $moyenne = $cours->notes->avg('note');
                                            @endphp
                                            @if($moyenne)
                                                <span class="badge bg-{{ $moyenne >= 10 ? 'success' : 'danger' }}">
                                                    {{ number_format($moyenne, 2) }}/20
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.cours.show', $cours->id) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.notes.index', ['cours_id' => $cours->id]) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    <i class="fas fa-chart-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun cours assigné pour le moment</p>
                            <a href="{{ route('admin.cours.create', ['enseignant_id' => $enseignant->id]) }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i> Assigner un cours
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques détaillées -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar text-success me-2"></i>
                        Répartition par département
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="deptChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line text-danger me-2"></i>
                        Évolution des moyennes
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="moyennesChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation de suppression -->
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
                    <p>Êtes-vous sûr de vouloir supprimer l'enseignant <strong>{{ $enseignant->prenom }} {{ $enseignant->nom }}</strong> ?</p>
                    <p class="text-danger mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Cette action est irréversible et supprimera également tous ses cours associés.
                    </p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.enseignants.destroy', $enseignant->id) }}" method="POST">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fonction pour ouvrir le modal de suppression
    function confirmDelete() {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Graphique de répartition par département
    const deptCtx = document.getElementById('deptChart').getContext('2d');
    new Chart(deptCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($deptLabels ?? ['Informatique', 'Mathématiques', 'Physique']) !!},
            datasets: [{
                data: {!! json_encode($deptData ?? [12, 8, 5]) !!},
                backgroundColor: ['#667eea', '#764ba2', '#ff6b6b']
            }]
        }
    });

    // Graphique d'évolution des moyennes
    const moyCtx = document.getElementById('moyennesChart').getContext('2d');
    new Chart(moyCtx, {
        type: 'line',
        data: {
            labels: ['Sem1 2024', 'Sem2 2024', 'Sem1 2025', 'Sem2 2025'],
            datasets: [{
                label: 'Moyenne générale',
                data: [14.2, 13.8, 15.1, 14.5],
                borderColor: '#667eea',
                tension: 0.1
            }]
        }
    });
</script>
@endpush
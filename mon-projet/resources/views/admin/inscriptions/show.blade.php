@extends('layouts.dashboard')

@section('title', 'Détails Inscription')
@section('page-title', 'Fiche d\'Inscription')

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
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-pen-to-square text-primary me-2"></i>
                                Inscription #{{ $inscription->id }}
                            </h4>
                            <p class="text-muted mb-0">
                                <span class="badge bg-info">{{ $inscription->anneeAcademique->annee ?? 'N/A' }}</span>
                                <span class="ms-2">
                                    <i class="fas fa-calendar me-1"></i> 
                                    {{ $inscription->date_inscription->format('d/m/Y') }}
                                </span>
                            </p>
                        </div>
                        <div>
                            @if($inscription->statut == 'en_attente')
                                <form action="{{ route('admin.inscriptions.validate', $inscription->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success" 
                                            onclick="return confirm('Valider cette inscription?')">
                                        <i class="fas fa-check me-1"></i> Valider
                                    </button>
                                </form>
                                
                                <button type="button" class="btn btn-danger" 
                                        onclick="showRejectModal()">
                                    <i class="fas fa-times me-1"></i> Rejeter
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.inscriptions.index') }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="row">
        <!-- Colonne gauche - Étudiant -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-graduate text-primary me-2"></i>
                        Informations étudiant
                    </h5>
                </div>
                <div class="card-body">
                    @if($inscription->etudiant)
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-user-graduate fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">
                                    <a href="{{ route('admin.etudiants.show', $inscription->etudiant->id) }}">
                                        {{ $inscription->etudiant->prenom }} {{ $inscription->etudiant->nom }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-0">
                                    Matricule: <strong>{{ $inscription->etudiant->matricule }}</strong>
                                </p>
                            </div>
                        </div>
                        
                        <table class="table table-sm">
                            <tr>
                                <th width="35%">Email</th>
                                <td>
                                    <a href="mailto:{{ $inscription->etudiant->email }}">
                                        {{ $inscription->etudiant->email }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Téléphone</th>
                                <td>{{ $inscription->etudiant->telephone ?? 'Non renseigné' }}</td>
                            </tr>
                            <tr>
                                <th>Date naissance</th>
                                <td>{{ $inscription->etudiant->date_naissance->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Lieu naissance</th>
                                <td>{{ $inscription->etudiant->lieu_naissance }}</td>
                            </tr>
                        </table>
                    @else
                        <p class="text-muted">Étudiant non trouvé</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Inscription -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle text-success me-2"></i>
                        Détails inscription
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th width="40%">Statut</th>
                            <td>
                                @if($inscription->statut == 'en_attente')
                                    <span class="badge bg-warning fs-6">En attente</span>
                                @elseif($inscription->statut == 'validee')
                                    <span class="badge bg-success fs-6">Validée</span>
                                @else
                                    <span class="badge bg-danger fs-6">Rejetée</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Année académique</th>
                            <td>
                                <span class="badge bg-info fs-6">
                                    {{ $inscription->anneeAcademique->annee ?? 'N/A' }}
                                </span>
                                @if($inscription->anneeAcademique->active ?? false)
                                    <span class="badge bg-success">Année en cours</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date d'inscription</th>
                            <td>{{ $inscription->date_inscription->format('d/m/Y à H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Date de validation</th>
                            <td>
                                @if($inscription->statut == 'validee' && $inscription->updated_at)
                                    {{ $inscription->updated_at->format('d/m/Y à H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @if($inscription->commentaire)
                        <tr>
                            <th>Commentaire</th>
                            <td>{{ $inscription->commentaire }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des inscriptions de l'étudiant -->
    @if($inscription->etudiant)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history text-info me-2"></i>
                        Historique des inscriptions de l'étudiant
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $historique = $inscription->etudiant->inscriptions()
                            ->with('anneeAcademique')
                            ->orderBy('created_at', 'desc')
                            ->get();
                    @endphp
                    
                    @if($historique->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Année</th>
                                        <th>Date inscription</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historique as $hist)
                                    <tr class="{{ $hist->id == $inscription->id ? 'table-primary' : '' }}">
                                        <td>{{ $hist->anneeAcademique->annee ?? 'N/A' }}</td>
                                        <td>{{ $hist->date_inscription->format('d/m/Y') }}</td>
                                        <td>
                                            @if($hist->statut == 'en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @elseif($hist->statut == 'validee')
                                                <span class="badge bg-success">Validée</span>
                                            @else
                                                <span class="badge bg-danger">Rejetée</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.inscriptions.show', $hist->id) }}" 
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
                        <p class="text-muted">Aucun historique</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistiques de l'année -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie text-warning me-2"></i>
                        Statistiques année {{ $inscription->anneeAcademique->annee ?? '' }}
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $statsAnnee = $inscription->anneeAcademique 
                            ? $inscription->anneeAcademique->inscriptions()
                                ->selectRaw('statut, count(*) as total')
                                ->groupBy('statut')
                                ->pluck('total', 'statut')
                            : collect();
                    @endphp
                    
                    <canvas id="statsChart"></canvas>
                    
                    <div class="row mt-3 text-center">
                        <div class="col-4">
                            <small class="text-muted">En attente</small>
                            <h5>{{ $statsAnnee['en_attente'] ?? 0 }}</h5>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Validées</small>
                            <h5>{{ $statsAnnee['validee'] ?? 0 }}</h5>
                        </div>
                        <div class="col-4">
                            <small class="text-muted">Rejetées</small>
                            <h5>{{ $statsAnnee['rejetee'] ?? 0 }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bell text-danger me-2"></i>
                        Notifications envoyées
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $notifications = App\Models\Notification::where('user_id', $inscription->etudiant->user_id ?? 0)
                            ->where('created_at', '>=', $inscription->created_at)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($notifications->count() > 0)
                        @foreach($notifications as $notif)
                            <div class="alert alert-{{ $notif->type }} mb-2">
                                <strong>{{ $notif->titre }}</strong>
                                <p class="mb-0 small">{{ $notif->message }}</p>
                                <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Aucune notification récente</p>
                    @endif
                </div>
            </div>
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
                    Rejeter l'inscription #{{ $inscription->id }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.inscriptions.reject', $inscription->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Veuillez indiquer la raison du rejet :</p>
                    <textarea name="reason" class="form-control" rows="3" 
                              placeholder="Raison du rejet..." required></textarea>
                    
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="notify_student" name="notify_student" value="1" checked>
                        <label class="form-check-label" for="notify_student">
                            Notifier l'étudiant par email
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Annuler
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i> Confirmer le rejet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des statistiques
    const ctx = document.getElementById('statsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['En attente', 'Validées', 'Rejetées'],
            datasets: [{
                data: [
                    {{ $statsAnnee['en_attente'] ?? 0 }},
                    {{ $statsAnnee['validee'] ?? 0 }},
                    {{ $statsAnnee['rejetee'] ?? 0 }}
                ],
                backgroundColor: ['#ffc107', '#28a745', '#dc3545']
            }]
        }
    });

    function showRejectModal() {
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }
</script>
@endpush
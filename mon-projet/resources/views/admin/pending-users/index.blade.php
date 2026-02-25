@extends('layouts.dashboard')

@section('title', 'Demandes d\'inscription')
@section('page-title', 'Demandes en attente')

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
    <a href="{{ route('admin.pending-users.index') }}" class="active">
        <i class="fas fa-clock"></i> Demandes en attente
        @if(isset($demandes) && $demandes->count() > 0)
            <span class="badge bg-danger">{{ $demandes->count() }}</span>
        @endif
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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Demandes d'inscription en attente
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($demandes) && $demandes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Nom & Prénom</th>
                                        <th>Email</th>
                                        <th>Rôle</th>
                                        <th>Date demande</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($demandes as $demande)
                                    <tr>
                                        <td>{{ $demande->id }}</td>
                                        <td>
                                            <strong>{{ $demande->nom }}</strong> {{ $demande->prenom }}
                                        </td>
                                        <td>{{ $demande->email }}</td>
                                        <td>
                                            @if($demande->role == 'etudiant')
                                                <span class="badge bg-info">Étudiant</span>
                                            @else
                                                <span class="badge bg-success">Enseignant</span>
                                            @endif
                                        </td>
                                        <td>{{ $demande->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.pending-users.show', $demande->id) }}" 
                                               class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                            
                                            <form action="{{ route('admin.pending-users.approve', $demande->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Approuver cette demande ?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Approuver
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="showRejectModal({{ $demande->id }})">
                                                <i class="fas fa-times"></i> Rejeter
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination si nécessaire -->
                        @if(method_exists($demandes, 'links'))
                            <div class="mt-3">
                                {{ $demandes->links() }}
                            </div>
                        @endif
                        
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                            <h5>Aucune demande en attente</h5>
                            <p class="text-muted">Toutes les demandes ont été traitées.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Rejeter la demande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="" method="POST" id="rejectForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Veuillez indiquer la raison du rejet :</p>
                    <textarea name="rejection_reason" class="form-control" rows="3" 
                              placeholder="Raison du rejet..." required></textarea>
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
// Fonction pour afficher la modal de rejet
function showRejectModal(id) {
    'use strict';
    
    // Vérifier que bootstrap est chargé
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap JS non chargé');
        return;
    }
    
    try {
        // Construire l'URL
        const baseUrl = '{{ url("/") }}';
        const form = document.getElementById('rejectForm');
        
        if (!form) {
            console.error('Formulaire de rejet non trouvé');
            return;
        }
        
        // Mettre à jour l'action du formulaire
        form.action = baseUrl + '/admin/pending-users/' + id + '/reject';
        
        // Afficher la modal
        const modalElement = document.getElementById('rejectModal');
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        
    } catch (error) {
        console.error('Erreur lors de l\'ouverture de la modal:', error);
    }
}

// Attendre que le DOM soit chargé
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Vérifier que le formulaire existe et réinitialiser si nécessaire
    const rejectForm = document.getElementById('rejectForm');
    if (rejectForm) {
        rejectForm.addEventListener('submit', function() {
            // Désactiver le bouton pour éviter les doubles soumissions
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Traitement...';
            }
        });
    }
    
    // Nettoyer la modal quand elle est fermée
    const modalElement = document.getElementById('rejectModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('rejectForm');
            if (form) {
                form.reset(); // Réinitialiser le formulaire
                
                // Réactiver le bouton si désactivé
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-times me-1"></i> Rejeter';
                }
            }
        });
    }
});
</script>
@endpush
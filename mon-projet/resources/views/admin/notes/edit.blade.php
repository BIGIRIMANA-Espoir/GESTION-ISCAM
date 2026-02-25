@extends('layouts.dashboard')

@section('title', 'Modifier une Note')
@section('page-title', 'Modification Note')

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-edit text-warning me-2"></i>
                            Modification de la note
                        </h5>
                        <div>
                            <a href="{{ route('admin.notes.show', $note->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye me-1"></i> Voir détails
                            </a>
                            <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informations de la note actuelle -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-primary">
                                        <i class="fas fa-graduation-cap me-2"></i>
                                        Étudiant
                                    </h6>
                                    <p class="mb-0">
                                        <strong>{{ $note->etudiant->prenom ?? '' }} {{ $note->etudiant->nom ?? '' }}</strong>
                                        <br>
                                        <small class="text-muted">Matricule: {{ $note->etudiant->matricule ?? '' }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="text-success">
                                        <i class="fas fa-book me-2"></i>
                                        Cours
                                    </h6>
                                    <p class="mb-0">
                                        <strong>{{ $note->cours->nom_cours ?? '' }}</strong>
                                        <br>
                                        <small class="text-muted">Code: {{ $note->cours->code_cours ?? '' }}</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire de modification -->
                    <form action="{{ route('admin.notes.update', $note->id) }}" method="POST" id="editNoteForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Note -->
                            <div class="col-md-4 mb-3">
                                <label for="note" class="form-label">
                                    Note <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control form-control-lg @error('note') is-invalid @enderror" 
                                       id="note" 
                                       name="note" 
                                       step="0.01" 
                                       min="0" 
                                       max="20" 
                                       value="{{ old('note', $note->note) }}" 
                                       required>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Note sur 20</small>
                            </div>

                            <!-- Session -->
                            <div class="col-md-4 mb-3">
                                <label for="session" class="form-label">
                                    Session
                                </label>
                                <select class="form-select form-select-lg" id="session" name="session">
                                    <option value="normale" {{ old('session', $note->session) == 'normale' ? 'selected' : '' }}>
                                        Normale
                                    </option>
                                    <option value="rattrapage" {{ old('session', $note->session) == 'rattrapage' ? 'selected' : '' }}>
                                        Rattrapage
                                    </option>
                                </select>
                            </div>

                            <!-- Année académique -->
                            <div class="col-md-4 mb-3">
                                <label for="annee_academique" class="form-label">
                                    Année académique
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="annee_academique" 
                                       name="annee_academique" 
                                       value="{{ old('annee_academique', $note->annee_academique) }}"
                                       readonly
                                       disabled>
                                <small class="text-muted">Non modifiable</small>
                            </div>
                        </div>

                        <!-- Historique des modifications -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-history me-2"></i>
                                            Historique de la note
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small class="text-muted">Date création:</small>
                                                <br>
                                                <strong>{{ $note->created_at->format('d/m/Y H:i') }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Dernière modif:</small>
                                                <br>
                                                <strong>{{ $note->updated_at->format('d/m/Y H:i') }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Note originale:</small>
                                                <br>
                                                <span class="badge bg-info">{{ $note->note }}/20</span>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Session originale:</small>
                                                <br>
                                                <span class="badge bg-info">{{ $note->session }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Impact sur les statistiques -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-chart-line me-2"></i>
                                    <strong>Impact de la modification:</strong>
                                    La modification de cette note affectera la moyenne de l'étudiant et les statistiques du cours.
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="fas fa-save me-2"></i> Mettre à jour
                                </button>
                                <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times me-2"></i> Annuler
                                </a>
                                
                                <!-- Bouton suppression avec confirmation -->
                                <button type="button" class="btn btn-danger btn-lg float-end" 
                                        onclick="confirmDelete()">
                                    <i class="fas fa-trash me-2"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Formulaire de suppression caché -->
                    <form id="delete-form" action="{{ route('admin.notes.destroy', $note->id) }}" 
                          method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation suppression -->
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
                <p>Êtes-vous sûr de vouloir supprimer cette note ?</p>
                <div class="alert alert-warning">
                    <strong>{{ $note->etudiant->prenom ?? '' }} {{ $note->etudiant->nom ?? '' }}</strong>
                    <br>
                    {{ $note->cours->nom_cours ?? '' }}: <strong>{{ $note->note }}/20</strong> ({{ $note->session }})
                </div>
                <p class="text-danger mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Cette action est irréversible et affectera les statistiques.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Annuler
                </button>
                <button type="button" class="btn btn-danger" onclick="deleteNote()">
                    <i class="fas fa-trash me-1"></i> Supprimer définitivement
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de justification modification -->
<div class="modal fade" id="justifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-pen me-2"></i>
                    Justification de la modification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Veuillez justifier cette modification (optionnel mais recommandé) :</p>
                <textarea id="justification" class="form-control" rows="3" 
                          placeholder="Ex: Erreur de saisie, rattrapage, etc."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Annuler
                </button>
                <button type="button" class="btn btn-warning" onclick="submitWithJustification()">
                    <i class="fas fa-save me-1"></i> Enregistrer avec justification
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Confirmation de suppression
    function confirmDelete() {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    
    function deleteNote() {
        document.getElementById('delete-form').submit();
    }

    // Gestion de la justification avant modification
    let originalNote = {{ $note->note }};
    let originalSession = "{{ $note->session }}";
    
    document.getElementById('editNoteForm').addEventListener('submit', function(e) {
        const newNote = parseFloat(document.getElementById('note').value);
        const newSession = document.getElementById('session').value;
        
        // Vérifier si la note a changé
        if (newNote !== originalNote || newSession !== originalSession) {
            e.preventDefault();
            
            // Stocker les données pour soumission après justification
            window.pendingSubmission = {
                note: newNote,
                session: newSession
            };
            
            new bootstrap.Modal(document.getElementById('justifyModal')).show();
        }
    });

    // Soumission avec justification
    function submitWithJustification() {
        const justification = document.getElementById('justification').value;
        
        // Ajouter la justification au formulaire
        const form = document.getElementById('editNoteForm');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'justification';
        input.value = justification;
        form.appendChild(input);
        
        // Soumettre
        form.submit();
    }

    // Validation en temps réel
    document.getElementById('note').addEventListener('input', function() {
        const value = parseFloat(this.value);
        const submitBtn = document.querySelector('button[type="submit"]');
        
        if (value < 0 || value > 20) {
            this.classList.add('is-invalid');
            submitBtn.disabled = true;
        } else {
            this.classList.remove('is-invalid');
            submitBtn.disabled = false;
            
            // Alerte si changement important
            if (Math.abs(value - originalNote) >= 5) {
                if (!document.getElementById('bigChangeAlert')) {
                    const alert = document.createElement('div');
                    alert.id = 'bigChangeAlert';
                    alert.className = 'alert alert-warning mt-2';
                    alert.innerHTML = `
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Modification importante de plus de 5 points. Une justification sera requise.
                    `;
                    this.parentNode.appendChild(alert);
                }
            } else {
                const alert = document.getElementById('bigChangeAlert');
                if (alert) alert.remove();
            }
        }
    });
</script>
@endpush
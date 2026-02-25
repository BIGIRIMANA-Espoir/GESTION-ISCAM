@extends('layouts.dashboard')

@section('title', 'Nouvelle Inscription')
@section('page-title', 'Créer une Inscription')

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-pen-to-square text-primary me-2"></i>
                            Formulaire d'inscription
                        </h5>
                        <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.inscriptions.store') }}" method="POST" id="inscriptionForm">
                        @csrf

                        <div class="row">
                            <!-- Sélection de l'étudiant -->
                            <div class="col-md-6 mb-3">
                                <label for="etudiant_id" class="form-label">
                                    Étudiant <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('etudiant_id') is-invalid @enderror" 
                                        id="etudiant_id" 
                                        name="etudiant_id" 
                                        required>
                                    <option value="">Sélectionner un étudiant</option>
                                    @foreach($etudiants as $etudiant)
                                        <option value="{{ $etudiant->id }}" 
                                            {{ old('etudiant_id', request('etudiant_id')) == $etudiant->id ? 'selected' : '' }}>
                                            {{ $etudiant->prenom }} {{ $etudiant->nom }} ({{ $etudiant->matricule }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('etudiant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sélection de l'année académique -->
                            <div class="col-md-6 mb-3">
                                <label for="annee_academique_id" class="form-label">
                                    Année académique <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('annee_academique_id') is-invalid @enderror" 
                                        id="annee_academique_id" 
                                        name="annee_academique_id" 
                                        required>
                                    <option value="">Sélectionner une année</option>
                                    @foreach($annees as $annee)
                                        <option value="{{ $annee->id }}" 
                                            {{ old('annee_academique_id') == $annee->id ? 'selected' : '' }}>
                                            {{ $annee->annee }} 
                                            @if($annee->active)
                                                <span class="badge bg-success">(Active)</span>
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('annee_academique_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Date d'inscription -->
                            <div class="col-md-6 mb-3">
                                <label for="date_inscription" class="form-label">
                                    Date d'inscription
                                </label>
                                <input type="date" 
                                       class="form-control @error('date_inscription') is-invalid @enderror" 
                                       id="date_inscription" 
                                       name="date_inscription" 
                                       value="{{ old('date_inscription', date('Y-m-d')) }}">
                                @error('date_inscription')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Statut -->
                            <div class="col-md-6 mb-3">
                                <label for="statut" class="form-label">
                                    Statut initial
                                </label>
                                <select class="form-select" id="statut" name="statut">
                                    <option value="en_attente" selected>En attente</option>
                                    <option value="validee">Validée directement</option>
                                </select>
                                <small class="text-muted">L'étudiant recevra une notification selon le statut choisi</small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Informations complémentaires -->
                            <div class="col-md-12 mb-3">
                                <label for="commentaire" class="form-label">
                                    Commentaire / Remarque
                                </label>
                                <textarea class="form-control" 
                                          id="commentaire" 
                                          name="commentaire" 
                                          rows="3">{{ old('commentaire') }}</textarea>
                            </div>
                        </div>

                        <!-- Récapitulatif et vérification -->
                        <div class="row mt-4" id="recapSection" style="display: none;">
                            <div class="col-12">
                                <div class="card border-info">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Vérification des inscriptions existantes
                                        </h6>
                                    </div>
                                    <div class="card-body" id="verificationResult">
                                        <!-- Résultat chargé via AJAX -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Enregistrer l'inscription
                                </button>
                                <a href="{{ route('admin.inscriptions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Variables pour stocker les sélections
    let selectedEtudiant = document.getElementById('etudiant_id');
    let selectedAnnee = document.getElementById('annee_academique_id');
    let recapSection = document.getElementById('recapSection');

    // Fonction pour vérifier les inscriptions existantes
    function checkExistingInscriptions() {
        const etudiantId = selectedEtudiant.value;
        const anneeId = selectedAnnee.value;
        
        if (etudiantId && anneeId) {
            // Simulation de vérification (à remplacer par un vrai appel AJAX)
            const result = document.getElementById('verificationResult');
            
            // Version statique pour la démo
            result.innerHTML = `
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Vérification des inscriptions en cours...
                </div>
            `;
            
            recapSection.style.display = 'block';
            
            // Simuler une vérification après 1 seconde
            setTimeout(() => {
                // Ici vous feriez un vrai appel AJAX
                result.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Aucune inscription existante pour cet étudiant en ${selectedAnnee.options[selectedAnnee.selectedIndex].text.split(' ')[0]}
                    </div>
                `;
            }, 1000);
        } else {
            recapSection.style.display = 'none';
        }
    }

    // Écouter les changements
    selectedEtudiant.addEventListener('change', checkExistingInscriptions);
    selectedAnnee.addEventListener('change', checkExistingInscriptions);

    // Validation du formulaire
    document.getElementById('inscriptionForm').addEventListener('submit', function(e) {
        if (!selectedEtudiant.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un étudiant');
            return false;
        }
        
        if (!selectedAnnee.value) {
            e.preventDefault();
            alert('Veuillez sélectionner une année académique');
            return false;
        }
        
        // Confirmation pour les inscriptions validées directement
        const statut = document.getElementById('statut').value;
        if (statut === 'validee') {
            if (!confirm('Vous allez valider directement cette inscription. L\'étudiant recevra une confirmation immédiate. Continuer?')) {
                e.preventDefault();
                return false;
            }
        }
    });

    // Si un étudiant est pré-sélectionné (via l'URL), déclencher la vérification
    if (selectedEtudiant.value && selectedAnnee.value) {
        checkExistingInscriptions();
    }
</script>
@endpush
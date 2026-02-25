@extends('layouts.dashboard')

@section('title', 'Demande d\'Inscription')
@section('page-title', 'Nouvelle Demande d\'Inscription')

@section('menu')
    <a href="{{ route('etudiant.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('etudiant.notes.index') }}">
        <i class="fas fa-chart-line"></i> Mes notes
    </a>
    <a href="{{ route('etudiant.inscriptions.index') }}" class="active">
        <i class="fas fa-pen-to-square"></i> Mes inscriptions
    </a>
    <a href="{{ route('etudiant.profile') }}">
        <i class="fas fa-user"></i> Mon profil
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="fas fa-pen-to-square me-2"></i>
                                Demande d'inscription
                            </h4>
                            <p class="mb-0 opacity-75">Année académique {{ $anneeCouranteObj->annee ?? date('Y').'-'.(date('Y')+1) }}</p>
                        </div>
                        <a href="{{ route('etudiant.inscriptions.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Retour
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vérification statut -->
    @if($dejaInscrit ?? false)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention !</strong> Vous avez déjà une demande d'inscription en cours pour l'année {{ $anneeCouranteObj->annee ?? date('Y').'-'.(date('Y')+1) }}.
                    <a href="{{ route('etudiant.inscriptions.index') }}" class="alert-link">Voir mes inscriptions</a>
                </div>
            </div>
        </div>
    @else
        <!-- Formulaire d'inscription -->
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-edit text-primary me-2"></i>
                            Formulaire de demande d'inscription
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('etudiant.inscriptions.store') }}" method="POST" id="inscriptionForm" enctype="multipart/form-data">
                            @csrf

                            <!-- Année académique (champ caché car une seule option) -->
                            <input type="hidden" name="annee_academique_id" value="{{ $anneeCouranteObj->id }}">

                            <!-- Informations personnelles (pré-remplies) -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary">
                                        <i class="fas fa-user me-2"></i>Mes informations
                                    </h6>
                                    <hr>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nom</label>
                                    <input type="text" class="form-control bg-light" 
                                           value="{{ auth()->user()->etudiant->nom ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" class="form-control bg-light" 
                                           value="{{ auth()->user()->etudiant->prenom ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Matricule</label>
                                    <input type="text" class="form-control bg-light" 
                                           value="{{ auth()->user()->etudiant->matricule ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control bg-light" 
                                           value="{{ auth()->user()->email ?? '' }}" readonly>
                                </div>
                            </div>

                            <!-- Documents à fournir -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <h6 class="text-success">
                                        <i class="fas fa-file-alt me-2"></i>Documents à fournir
                                    </h6>
                                    <hr>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cin" class="form-label">
                                        Copie de la carte d'identité <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('cin') is-invalid @enderror" 
                                           id="cin" name="cin" accept=".pdf,.jpg,.png" required>
                                    <small class="text-muted">Format PDF, JPG ou PNG (max 2 Mo)</small>
                                    @error('cin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="releves" class="form-label">
                                        Relevés de notes (N-1) <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('releves') is-invalid @enderror" 
                                           id="releves" name="releves" accept=".pdf" required>
                                    <small class="text-muted">Format PDF uniquement (max 5 Mo)</small>
                                    @error('releves')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="photo" class="form-label">
                                        Photo d'identité <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                           id="photo" name="photo" accept=".jpg,.png" required>
                                    <small class="text-muted">Format JPG ou PNG (max 1 Mo)</small>
                                    @error('photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr>

                            <!-- Boutons de soumission -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-paper-plane me-2"></i> Soumettre ma demande
                                    </button>
                                    <a href="{{ route('etudiant.inscriptions.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar avec résumé -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Résumé
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Année d'inscription</span>
                                <strong>{{ $anneeCouranteObj->annee }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span>Documents requis</span>
                                <strong>3</strong>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Prochaines étapes
                        </h5>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li class="mb-2">Soumission du dossier</li>
                            <li class="mb-2">Vérification par l'administration (2-3 jours)</li>
                            <li class="mb-2">Validation définitive</li>
                            <li>Édition de la carte étudiante</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Validation du formulaire
    document.getElementById('inscriptionForm')?.addEventListener('submit', function(e) {
        // Vérification des fichiers
        const files = ['cin', 'releves', 'photo'];
        let allFilesOk = true;
        
        files.forEach(fileId => {
            const input = document.getElementById(fileId);
            if (input && input.files.length === 0) {
                allFilesOk = false;
                input.classList.add('is-invalid');
            }
        });
        
        if (!allFilesOk) {
            e.preventDefault();
            alert('Veuillez fournir tous les documents requis');
            return false;
        }
        
        // Confirmation finale
        return confirm('Confirmez-vous l\'envoi de votre demande d\'inscription ?');
    });
</script>
@endpush
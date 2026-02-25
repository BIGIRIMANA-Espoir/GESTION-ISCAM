@extends('layouts.dashboard')

@section('title', 'Ajouter un Étudiant')
@section('page-title', 'Nouvel Étudiant')

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            Formulaire d'ajout d'étudiant
                        </h5>
                        <a href="{{ route('admin.etudiants.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.etudiants.store') }}" method="POST" id="etudiantForm">
                        @csrf

                        <div class="row">
                            <!-- Matricule -->
                            <div class="col-md-4 mb-3">
                                <label for="matricule" class="form-label">
                                    Matricule <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('matricule') is-invalid @enderror" 
                                       id="matricule" 
                                       name="matricule" 
                                       value="{{ old('matricule') }}" 
                                       required
                                       placeholder="Ex: ISC2024001">
                                @error('matricule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format: ISC + année + numéro (ex: ISC2024001)</small>
                            </div>

                            <!-- Nom -->
                            <div class="col-md-4 mb-3">
                                <label for="nom" class="form-label">
                                    Nom <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nom') is-invalid @enderror" 
                                       id="nom" 
                                       name="nom" 
                                       value="{{ old('nom') }}" 
                                       required>
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div class="col-md-4 mb-3">
                                <label for="prenom" class="form-label">
                                    Prénom <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('prenom') is-invalid @enderror" 
                                       id="prenom" 
                                       name="prenom" 
                                       value="{{ old('prenom') }}" 
                                       required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Date de naissance -->
                            <div class="col-md-4 mb-3">
                                <label for="date_naissance" class="form-label">
                                    Date de naissance <span class="text-danger">*</span>
                                </label>
                                <input type="date" 
                                       class="form-control @error('date_naissance') is-invalid @enderror" 
                                       id="date_naissance" 
                                       name="date_naissance" 
                                       value="{{ old('date_naissance') }}" 
                                       required>
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lieu de naissance -->
                            <div class="col-md-4 mb-3">
                                <label for="lieu_naissance" class="form-label">
                                    Lieu de naissance <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                       id="lieu_naissance" 
                                       name="lieu_naissance" 
                                       value="{{ old('lieu_naissance') }}" 
                                       required>
                                @error('lieu_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sexe -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    Sexe <span class="text-danger">*</span>
                                </label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="sexe" 
                                               id="sexe_m" 
                                               value="M" 
                                               {{ old('sexe') == 'M' ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="sexe_m">
                                            <i class="fas fa-mars text-primary"></i> Masculin
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="sexe" 
                                               id="sexe_f" 
                                               value="F" 
                                               {{ old('sexe') == 'F' ? 'checked' : '' }}
                                               required>
                                        <label class="form-check-label" for="sexe_f">
                                            <i class="fas fa-venus text-danger"></i> Féminin
                                        </label>
                                    </div>
                                </div>
                                @error('sexe')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email -->
                            <div class="col-md-4 mb-3">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="exemple@iscam.bi">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Téléphone -->
                            <div class="col-md-4 mb-3">
                                <label for="telephone" class="form-label">
                                    Téléphone
                                </label>
                                <input type="tel" 
                                       class="form-control @error('telephone') is-invalid @enderror" 
                                       id="telephone" 
                                       name="telephone" 
                                       value="{{ old('telephone') }}"
                                       placeholder="+257 XX XX XX XX">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Adresse -->
                            <div class="col-md-4 mb-3">
                                <label for="adresse" class="form-label">
                                    Adresse <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('adresse') is-invalid @enderror" 
                                       id="adresse" 
                                       name="adresse" 
                                       value="{{ old('adresse') }}" 
                                       required
                                       placeholder="Quartier, Avenue, N°">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Année d'entrée - DYNAMISÉE -->
                            <div class="col-md-6 mb-3">
                                <label for="annee_entree" class="form-label">
                                    Année d'entrée
                                </label>
                                <select class="form-select" id="annee_entree" name="annee_entree">
                                    <option value="">Sélectionner une année</option>
                                    @php
                                        $anneeActuelle = (int)date('Y');
                                        $anneeDebut = $anneeActuelle - 5;
                                    @endphp
                                    @for($year = $anneeActuelle; $year >= $anneeDebut; $year--)
                                        <option value="{{ $year }}" 
                                            {{ old('annee_entree') == $year ? 'selected' : (old('annee_entree') ? '' : ($year == $anneeActuelle ? 'selected' : '')) }}>
                                            {{ $year }} - {{ $year+1 }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Année académique en cours (NOUVEAU CHAMP AJOUTÉ) -->
                            <div class="col-md-6 mb-3">
                                <label for="annee_academique" class="form-label">
                                    Année académique
                                </label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       id="annee_academique" 
                                       name="annee_academique" 
                                       value="{{ $anneeEnCours ?? '2025-2026' }}" 
                                       readonly
                                       style="cursor: not-allowed;">
                                <small class="text-muted">
                                    <i class="fas fa-lock me-1"></i> Année académique en cours
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <!-- DÉPARTEMENT -->
                            <div class="col-md-12 mb-3">
                                <label for="departement_id" class="form-label">
                                    Département d'affectation
                                </label>
                                <select class="form-select" id="departement_id" name="departement_id">
                                    <option value="">Sélectionner un département</option>
                                    @foreach($departements as $id => $nom)
                                        <option value="{{ $id }}" 
                                            {{ old('departement_id') == $id ? 'selected' : '' }}>
                                            {{ $nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Créer compte -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="creer_compte" 
                                           name="creer_compte" 
                                           value="1"
                                           {{ old('creer_compte') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="creer_compte">
                                        Créer un compte utilisateur
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Champs mot de passe (cachés par défaut) -->
                        <div id="password_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">
                                        Mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           minlength="8"
                                           placeholder="Minimum 8 caractères">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmer le mot de passe
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Enregistrer
                                </button>
                                <a href="{{ route('admin.etudiants.index') }}" class="btn btn-secondary">
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
    // Afficher/masquer les champs de mot de passe
    document.getElementById('creer_compte').addEventListener('change', function() {
        document.getElementById('password_fields').style.display = this.checked ? 'block' : 'none';
    });

    // Validation du formulaire
    document.getElementById('etudiantForm').addEventListener('submit', function(e) {
        const matricule = document.getElementById('matricule').value;
        const email = document.getElementById('email').value;
        
        // Vérification simple du format email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Veuillez entrer une adresse email valide');
            return false;
        }
        
        // Vérification du matricule
        if (matricule.length < 5) {
            e.preventDefault();
            alert('Le matricule doit contenir au moins 5 caractères');
            return false;
        }

        // Validation du mot de passe si la case est cochée
        const creerCompte = document.getElementById('creer_compte').checked;
        if (creerCompte) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 8 caractères');
                return false;
            }
            
            if (password !== confirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas');
                return false;
            }
        }
    });
</script>
@endpush
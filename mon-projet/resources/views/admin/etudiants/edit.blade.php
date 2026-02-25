@extends('layouts.dashboard')

@section('title', 'Modifier un Étudiant')
@section('page-title', 'Modification Étudiant')

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
                            <i class="fas fa-user-edit text-warning me-2"></i>
                            Modification: <strong>{{ $etudiant->prenom }} {{ $etudiant->nom }}</strong>
                            <small class="text-muted ms-2">({{ $etudiant->matricule }})</small>
                        </h5>
                        <a href="{{ route('admin.etudiants.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.etudiants.update', $etudiant->id) }}" method="POST" id="etudiantForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Matricule (readonly car ne doit pas changer) -->
                            <div class="col-md-4 mb-3">
                                <label for="matricule" class="form-label">
                                    Matricule <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       id="matricule" 
                                       value="{{ $etudiant->matricule }}" 
                                       readonly
                                       disabled>
                                <small class="text-muted">Le matricule ne peut pas être modifié</small>
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
                                       value="{{ old('nom', $etudiant->nom) }}" 
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
                                       value="{{ old('prenom', $etudiant->prenom) }}" 
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
                                       value="{{ old('date_naissance', $etudiant->date_naissance->format('Y-m-d')) }}" 
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
                                       value="{{ old('lieu_naissance', $etudiant->lieu_naissance) }}" 
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
                                               {{ old('sexe', $etudiant->sexe) == 'M' ? 'checked' : '' }}
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
                                               {{ old('sexe', $etudiant->sexe) == 'F' ? 'checked' : '' }}
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
                                       value="{{ old('email', $etudiant->email) }}" 
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
                                       value="{{ old('telephone', $etudiant->telephone) }}"
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
                                       value="{{ old('adresse', $etudiant->adresse) }}" 
                                       required
                                       placeholder="Quartier, Avenue, N°">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Section changement de mot de passe (si compte utilisateur existe) -->
                        @if($etudiant->user_id)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-key me-2"></i>
                                            Compte utilisateur associé
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           id="changer_mdp" 
                                                           name="changer_mdp" 
                                                           value="1">
                                                    <label class="form-check-label" for="changer_mdp">
                                                        Changer le mot de passe
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="password_fields" style="display: none;">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="password" class="form-label">
                                                        Nouveau mot de passe
                                                    </label>
                                                    <input type="password" 
                                                           class="form-control @error('password') is-invalid @enderror" 
                                                           id="password" 
                                                           name="password">
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <hr class="my-4">

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i> Mettre à jour
                                </button>
                                <a href="{{ route('admin.etudiants.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <a href="{{ route('admin.etudiants.show', $etudiant->id) }}" 
                                   class="btn btn-info float-end">
                                    <i class="fas fa-eye me-1"></i> Voir détails
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
    document.getElementById('changer_mdp')?.addEventListener('change', function() {
        document.getElementById('password_fields').style.display = this.checked ? 'block' : 'none';
    });

    // Validation du formulaire
    document.getElementById('etudiantForm').addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Veuillez entrer une adresse email valide');
            return false;
        }
        
        // Validation du mot de passe si changement demandé
        const changerMdp = document.getElementById('changer_mdp');
        if (changerMdp && changerMdp.checked) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            
            if (password.length < 6) {
                e.preventDefault();
                alert('Le mot de passe doit contenir au moins 6 caractères');
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
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Demande d'inscription
                    </h4>
                    <p class="mb-0 small opacity-75">Tous les champs avec * sont obligatoires</p>
                </div>

                <div class="card-body p-4">
                    {{-- Ajout de autocomplete="off" ici pour aider le nettoyage --}}
                    <form method="POST" action="{{ route('register') }}" autocomplete="off">
                        @csrf

                        <div class="row">
                            <!-- Nom -->
                            <div class="col-md-6 mb-3">
                                <label for="nom" class="form-label">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    Nom <span class="text-danger">*</span>
                                </label>
                                <input id="nom" 
                                       type="text" 
                                       class="form-control @error('nom') is-invalid @enderror" 
                                       name="nom" 
                                       value="{{ old('nom') }}" 
                                       required 
                                       autocomplete="off"
                                       placeholder="Votre nom">
                                @error('nom')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Prénom -->
                            <div class="col-md-6 mb-3">
                                <label for="prenom" class="form-label">
                                    <i class="fas fa-user me-2 text-primary"></i>
                                    Prénom <span class="text-danger">*</span>
                                </label>
                                <input id="prenom" 
                                       type="text" 
                                       class="form-control @error('prenom') is-invalid @enderror" 
                                       name="prenom" 
                                       value="{{ old('prenom') }}" 
                                       required 
                                       autocomplete="off"
                                       placeholder="Votre prénom">
                                @error('prenom')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Email (NETTOYÉ : admin@iscam.bi supprimé) -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    Email <span class="text-danger">*</span>
                                </label>
                                <input id="email" 
                                       type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       value="" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="exemple@iscam.bi">
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Rôle -->
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">
                                    <i class="fas fa-user-tag me-2 text-primary"></i>
                                    Vous êtes <span class="text-danger">*</span>
                                </label>
                                <select id="role" 
                                        class="form-select @error('role') is-invalid @enderror" 
                                        name="role" 
                                        required>
                                    <option value="">Sélectionner...</option>
                                    <option value="etudiant" {{ old('role') == 'etudiant' ? 'selected' : '' }}>
                                        Étudiant
                                    </option>
                                    <option value="enseignant" {{ old('role') == 'enseignant' ? 'selected' : '' }}>
                                        Enseignant
                                    </option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Téléphone -->
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone me-2 text-primary"></i>
                                    Téléphone
                                </label>
                                <input id="telephone" 
                                       type="text" 
                                       class="form-control @error('telephone') is-invalid @enderror" 
                                       name="telephone" 
                                       value="{{ old('telephone') }}" 
                                       autocomplete="off"
                                       placeholder="+257 XX XX XX XX">
                                @error('telephone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Adresse -->
                            <div class="col-md-6 mb-3">
                                <label for="adresse" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    Adresse
                                </label>
                                <input id="adresse" 
                                       type="text" 
                                       class="form-control @error('adresse') is-invalid @enderror" 
                                       name="adresse" 
                                       value="{{ old('adresse') }}" 
                                       autocomplete="off"
                                       placeholder="Votre adresse">
                                @error('adresse')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Date naissance -->
                            <div class="col-md-4 mb-3">
                                <label for="date_naissance" class="form-label">
                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                    Date naissance
                                </label>
                                <input id="date_naissance" 
                                       type="date" 
                                       class="form-control @error('date_naissance') is-invalid @enderror" 
                                       name="date_naissance" 
                                       value="{{ old('date_naissance') }}" 
                                       autocomplete="off">
                                @error('date_naissance')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Lieu naissance -->
                            <div class="col-md-4 mb-3">
                                <label for="lieu_naissance" class="form-label">
                                    <i class="fas fa-map-marker me-2 text-primary"></i>
                                    Lieu naissance
                                </label>
                                <input id="lieu_naissance" 
                                       type="text" 
                                       class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                       name="lieu_naissance" 
                                       value="{{ old('lieu_naissance') }}" 
                                       autocomplete="off"
                                       placeholder="Lieu de naissance">
                                @error('lieu_naissance')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Sexe -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-venus-mars me-2 text-primary"></i>
                                    Sexe
                                </label>
                                <div class="d-flex">
                                    <div class="form-check me-3">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="sexe" 
                                               id="sexe_m" 
                                               value="M" 
                                               {{ old('sexe') == 'M' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sexe_m">
                                            Masculin
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="sexe" 
                                               id="sexe_f" 
                                               value="F" 
                                               {{ old('sexe') == 'F' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="sexe_f">
                                            Féminin
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mot de passe (NETTOYÉ : Étoiles supprimées) -->
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    Mot de passe <span class="text-danger">*</span>
                                </label>
                                <input id="password" 
                                       type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Minimum 8 caractères">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirmation mot de passe -->
                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    Confirmer mot de passe <span class="text-danger">*</span>
                                </label>
                                <input id="password-confirm" 
                                       type="password" 
                                       class="form-control" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Confirmer votre mot de passe">
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                Envoyer ma demande
                            </button>
                        </div>

                        <!-- LIEN EN BAS -->
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                Demande accordée ?
                                <a href="{{ route('login') }}" class="text-primary fw-bold ms-1">
                                    Se connecter
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

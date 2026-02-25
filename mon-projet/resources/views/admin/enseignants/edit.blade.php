@extends('layouts.dashboard')

@section('title', 'Modifier un Enseignant')
@section('page-title', 'Modification Enseignant')

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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chalkboard-teacher text-warning me-2"></i>
                            Modification: <strong>{{ $enseignant->prenom }} {{ $enseignant->nom }}</strong>
                            <small class="text-muted ms-2">({{ $enseignant->matricule }})</small>
                        </h5>
                        <a href="{{ route('admin.enseignants.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.enseignants.update', $enseignant->id) }}" method="POST" id="enseignantForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Matricule (readonly) -->
                            <div class="col-md-4 mb-3">
                                <label for="matricule" class="form-label">
                                    Matricule <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       id="matricule" 
                                       value="{{ $enseignant->matricule }}" 
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
                                       value="{{ old('nom', $enseignant->nom) }}" 
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
                                       value="{{ old('prenom', $enseignant->prenom) }}" 
                                       required>
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Grade -->
                            <div class="col-md-4 mb-3">
                                <label for="grade" class="form-label">
                                    Grade <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('grade') is-invalid @enderror" 
                                        id="grade" 
                                        name="grade" 
                                        required>
                                    <option value="">Sélectionner un grade</option>
                                    <option value="Professeur" {{ old('grade', $enseignant->grade) == 'Professeur' ? 'selected' : '' }}>Professeur</option>
                                    <option value="Maître de Conférences" {{ old('grade', $enseignant->grade) == 'Maître de Conférences' ? 'selected' : '' }}>Maître de Conférences</option>
                                    <option value="Maître-Assistant" {{ old('grade', $enseignant->grade) == 'Maître-Assistant' ? 'selected' : '' }}>Maître-Assistant</option>
                                    <option value="Assistant" {{ old('grade', $enseignant->grade) == 'Assistant' ? 'selected' : '' }}>Assistant</option>
                                    <option value="Chargé de Cours" {{ old('grade', $enseignant->grade) == 'Chargé de Cours' ? 'selected' : '' }}>Chargé de Cours</option>
                                </select>
                                @error('grade')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Spécialité -->
                            <div class="col-md-4 mb-3">
                                <label for="specialite" class="form-label">
                                    Spécialité <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('specialite') is-invalid @enderror" 
                                       id="specialite" 
                                       name="specialite" 
                                       value="{{ old('specialite', $enseignant->specialite) }}" 
                                       required>
                                @error('specialite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Département -->
                            <div class="col-md-4 mb-3">
                                <label for="departement_id" class="form-label">
                                    Département <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('departement_id') is-invalid @enderror" 
                                        id="departement_id" 
                                        name="departement_id" 
                                        required>
                                    <option value="">Sélectionner un département</option>
                                    @foreach($departements as $id => $nom)
                                        <option value="{{ $id }}" 
                                            {{ old('departement_id', $enseignant->departement_id) == $id ? 'selected' : '' }}>
                                            {{ $nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
                                       value="{{ old('email', $enseignant->email) }}" 
                                       required>
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
                                       value="{{ old('telephone', $enseignant->telephone) }}">
                                @error('telephone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Disponibilité -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Statut</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="disponible" 
                                               id="disponible_oui" 
                                               value="1"
                                               {{ old('disponible', $enseignant->disponible ?? '1') == '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="disponible_oui">
                                            <i class="fas fa-check-circle text-success"></i> Disponible
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="disponible" 
                                               id="disponible_non" 
                                               value="0"
                                               {{ old('disponible', $enseignant->disponible ?? '1') == '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="disponible_non">
                                            <i class="fas fa-times-circle text-danger"></i> Indisponible
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section changement de mot de passe (si compte utilisateur existe) -->
                        @if($enseignant->user_id)
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
                                                           name="password"
                                                           placeholder="Minimum 6 caractères">
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

                        <!-- Observations -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="observations" class="form-label">
                                    Observations
                                </label>
                                <textarea class="form-control" 
                                          id="observations" 
                                          name="observations" 
                                          rows="3">{{ old('observations', $enseignant->observations ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i> Mettre à jour
                                </button>
                                <a href="{{ route('admin.enseignants.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <a href="{{ route('admin.enseignants.show', $enseignant->id) }}" 
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
    const changerMdp = document.getElementById('changer_mdp');
    if (changerMdp) {
        changerMdp.addEventListener('change', function() {
            document.getElementById('password_fields').style.display = this.checked ? 'block' : 'none';
        });
    }

    // Validation du formulaire
    document.getElementById('enseignantForm').addEventListener('submit', function(e) {
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
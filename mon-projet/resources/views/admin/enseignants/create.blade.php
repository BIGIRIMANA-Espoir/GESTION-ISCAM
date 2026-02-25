@extends('layouts.dashboard')

@section('title', 'Ajouter un Enseignant')
@section('page-title', 'Nouvel Enseignant')

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
                            <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                            Formulaire d'ajout d'enseignant
                        </h5>
                        <a href="{{ route('admin.enseignants.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.enseignants.store') }}" method="POST" id="enseignantForm">
                        @csrf

                        <!-- Matricule -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="matricule" class="form-label">Matricule <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="matricule" name="matricule" value="{{ old('matricule') }}" required>
                            </div>

                            <!-- Nom -->
                            <div class="col-md-4 mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" required>
                            </div>

                            <!-- Prénom -->
                            <div class="col-md-4 mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                            </div>
                        </div>

                        <!-- Grade et Spécialité -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                                <select class="form-control" id="grade" name="grade" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Professeur">Professeur</option>
                                    <option value="Maître de Conférences">Maître de Conférences</option>
                                    <option value="Maître-Assistant">Maître-Assistant</option>
                                    <option value="Assistant">Assistant</option>
                                    <option value="Chargé de Cours">Chargé de Cours</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="specialite" class="form-label">Spécialité <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="specialite" name="specialite" value="{{ old('specialite') }}" required>
                            </div>

                            <!-- EMAIL CORRIGÉ AVEC AUTOCOMPLETE OFF -->
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
                                       placeholder="prenom.nom@iscam.bi"
                                       pattern=".+@iscam\.bi"
                                       title="L'email doit être de la forme prenom.nom@iscam.bi"
                                       autocomplete="off">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Format : prenom.nom@iscam.bi
                                </small>
                            </div>
                        </div>

                        <!-- Téléphone et Faculté -->
                        <div class="row">
                            <!-- TÉLÉPHONE AVEC CODE PAYS -->
                            <div class="col-md-4 mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <div class="input-group">
                                    <select class="form-select" id="code_pays" name="code_pays" style="max-width: 100px;">
                                        <option value="+257" selected>+257 (Burundi)</option>
                                        <option value="+255">+255 (Tanzanie)</option>
                                        <option value="+256">+256 (Ouganda)</option>
                                        <option value="+250">+250 (Rwanda)</option>
                                        <option value="+243">+243 (RDC)</option>
                                        <option value="+254">+254 (Kenya)</option>
                                    </select>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="telephone" 
                                           name="telephone" 
                                           value="{{ old('telephone') }}"
                                           placeholder="79 123 456"
                                           pattern="[0-9\s\-]{8,15}">
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ex: 79 123 456 (sans le +257)
                                </small>
                            </div>

                            <!-- FACULTÉ -->
                            <div class="col-md-4 mb-3">
                                <label for="faculte" class="form-label">Faculté <span class="text-danger">*</span></label>
                                <select class="form-control" id="faculte" name="faculte" required>
                                    <option value="">Choisir une faculté</option>
                                    <option value="FSI">FSI - Sciences de l'Ingénieur</option>
                                    <option value="FSG">FSG - Sciences de Gestion</option>
                                    <option value="TECH">Technologies Avancées</option>
                                </select>
                            </div>

                            <!-- DÉPARTEMENT (GROUPE) -->
                            <div class="col-md-4 mb-3">
                                <label for="departement_groupe" class="form-label">Département <span class="text-danger">*</span></label>
                                <select class="form-control" id="departement_groupe" name="departement_groupe" required>
                                    <option value="">D'abord choisir faculté</option>
                                </select>
                            </div>
                        </div>

                        <!-- SOUS-DÉPARTEMENT (pour TIC) -->
                        <div class="row" id="sous_departement_row" style="display: none;">
                            <div class="col-md-4 offset-md-8 mb-3">
                                <label for="sous_departement" class="form-label">Spécialisation TIC <span class="text-danger">*</span></label>
                                <select class="form-control" id="sous_departement" name="sous_departement">
                                    <option value="">Choisir</option>
                                    <option value="GL">GL - Génie Logiciel</option>
                                    <option value="RT">RT - Réseaux et Télécommunications</option>
                                </select>
                            </div>
                        </div>

                        <!-- Disponibilité -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Statut</label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="disponible" id="disponible_oui" value="1" checked>
                                    <label class="form-check-label" for="disponible_oui">Disponible</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="disponible" id="disponible_non" value="0">
                                    <label class="form-check-label" for="disponible_non">Indisponible</label>
                                </div>
                            </div>
                        </div>

                        <!-- Créer compte -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="creer_compte" name="creer_compte" value="1">
                                    <label class="form-check-label" for="creer_compte">Créer un compte utilisateur</label>
                                </div>
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div id="password_fields" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="password" name="password" minlength="8">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                        </div>

                        <!-- Champ caché pour département final -->
                        <input type="hidden" id="departement_final" name="departement_id">

                        <!-- Champ caché pour téléphone complet -->
                        <input type="hidden" id="telephone_complet" name="telephone">

                        <!-- Boutons -->
                        <hr>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('admin.enseignants.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Structure arborescente
    const arbreDepartements = {
        'FSI': [
            'GME (Génie Mécanique)',
            'GC (Génie Civil)',
            'TIC'
        ],
        'FSG': [
            'ECOPO (Économie Politique)',
            'ECORU (Économie Rurale)',
            'GESTION (Management)'
        ],
        'TECH': [
            'Techniques Nucléaires',
            'Drone',
            'SDA (Systèmes de Défense)'
        ]
    };

    const sousOptions = {
        'TIC': ['GL - Génie Logiciel', 'RT - Réseaux et Télécommunications']
    };

    // Mapping des départements vers leurs IDs (ADAPTE CES VALEURS SELON TA BASE)
    const departementsIds = {
        'GME (Génie Mécanique)': 1,
        'GC (Génie Civil)': 2,
        'GL - Génie Logiciel': 3,
        'RT - Réseaux et Télécommunications': 4,
        'ECOPO (Économie Politique)': 5,
        'ECORU (Économie Rurale)': 6,
        'GESTION (Management)': 7,
        'Techniques Nucléaires': 8,
        'Drone': 9,
        'SDA (Systèmes de Défense)': 10
    };

    // Éléments DOM
    const faculteSelect = document.getElementById('faculte');
    const groupeSelect = document.getElementById('departement_groupe');
    const sousRow = document.getElementById('sous_departement_row');
    const sousSelect = document.getElementById('sous_departement');
    const departementFinal = document.getElementById('departement_final');
    const codePays = document.getElementById('code_pays');
    const telephoneInput = document.getElementById('telephone');
    const telephoneComplet = document.getElementById('telephone_complet');

    // Quand faculté change
    faculteSelect.addEventListener('change', function() {
        let faculte = this.value;
        groupeSelect.innerHTML = '<option value="">Choisir département</option>';
        sousRow.style.display = 'none';
        departementFinal.value = '';

        if (faculte && arbreDepartements[faculte]) {
            arbreDepartements[faculte].forEach(function(item) {
                let option = document.createElement('option');
                option.value = item;
                option.textContent = item;
                groupeSelect.appendChild(option);
            });
        }
    });

    // Quand département groupe change
    groupeSelect.addEventListener('change', function() {
        let choix = this.value;

        if (choix === 'TIC') {
            sousRow.style.display = 'block';
            sousSelect.innerHTML = '<option value="">Choisir</option>';
            sousOptions['TIC'].forEach(function(opt) {
                let option = document.createElement('option');
                option.value = opt;
                option.textContent = opt;
                sousSelect.appendChild(option);
            });
            departementFinal.value = '';
        } else {
            sousRow.style.display = 'none';
            departementFinal.value = departementsIds[choix] || '';
        }
    });

    // Quand sous-département change
    sousSelect.addEventListener('change', function() {
        departementFinal.value = departementsIds[this.value] || '';
    });

    // Construire le numéro complet avant soumission
    function construireTelephoneComplet() {
        let code = codePays.value;
        let numero = telephoneInput.value.trim();
        
        if (numero) {
            // Nettoyer le numéro (enlever espaces, tirets)
            numero = numero.replace(/[\s\-]/g, '');
            telephoneComplet.value = code + numero;
        } else {
            telephoneComplet.value = '';
        }
    }

    // Mettre à jour le champ caché quand le téléphone change
    telephoneInput.addEventListener('input', construireTelephoneComplet);
    codePays.addEventListener('change', construireTelephoneComplet);

    // Avant soumission
    document.getElementById('enseignantForm').addEventListener('submit', function(e) {
        if (!departementFinal.value) {
            e.preventDefault();
            alert('Veuillez sélectionner un département complet');
            return false;
        }

        // Construire le téléphone complet
        construireTelephoneComplet();

        // Validation email
        let email = document.getElementById('email').value;
        if (!email.endsWith('@iscam.bi')) {
            e.preventDefault();
            alert('L\'email doit se terminer par @iscam.bi');
            return false;
        }

        // Validation mot de passe si coché
        let creerCompte = document.getElementById('creer_compte').checked;
        if (creerCompte) {
            let password = document.getElementById('password').value;
            let confirm = document.getElementById('password_confirmation').value;
            
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

    // Afficher mot de passe si coché
    document.getElementById('creer_compte').addEventListener('change', function() {
        document.getElementById('password_fields').style.display = this.checked ? 'block' : 'none';
    });

    // Si anciennes valeurs existent
    @if(old('faculte'))
        setTimeout(function() {
            document.getElementById('faculte').value = "{{ old('faculte') }}";
            document.getElementById('faculte').dispatchEvent(new Event('change'));
            
            @if(old('departement_groupe'))
                setTimeout(function() {
                    document.getElementById('departement_groupe').value = "{{ old('departement_groupe') }}";
                    document.getElementById('departement_groupe').dispatchEvent(new Event('change'));
                }, 200);
            @endif
            
            @if(old('sous_departement'))
                setTimeout(function() {
                    document.getElementById('sous_departement').value = "{{ old('sous_departement') }}";
                    document.getElementById('sous_departement').dispatchEvent(new Event('change'));
                }, 300);
            @endif
        }, 100);
    @endif
</script>
@endsection
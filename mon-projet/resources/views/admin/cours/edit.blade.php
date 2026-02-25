@extends('layouts.dashboard')

@section('title', 'Modifier un Cours')
@section('page-title', 'Modification Cours')

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
    <a href="{{ route('admin.cours.index') }}" class="active">
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
                            <i class="fas fa-book text-warning me-2"></i>
                            Modification: <strong>{{ $cours->nom_cours }}</strong>
                            <small class="text-muted ms-2">({{ $cours->code_cours }})</small>
                        </h5>
                        <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.cours.update', $cours->id) }}" method="POST" id="coursForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Code du cours (readonly) -->
                            <div class="col-md-4 mb-3">
                                <label for="code_cours" class="form-label">
                                    Code du cours <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control bg-light" 
                                       id="code_cours" 
                                       value="{{ $cours->code_cours }}" 
                                       readonly
                                       disabled>
                                <small class="text-muted">Le code ne peut pas être modifié</small>
                            </div>

                            <!-- Nom du cours -->
                            <div class="col-md-8 mb-3">
                                <label for="nom_cours" class="form-label">
                                    Nom du cours <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('nom_cours') is-invalid @enderror" 
                                       id="nom_cours" 
                                       name="nom_cours" 
                                       value="{{ old('nom_cours', $cours->nom_cours) }}" 
                                       required>
                                @error('nom_cours')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
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
                                            {{ old('departement_id', $cours->departement_id) == $id ? 'selected' : '' }}>
                                            {{ $nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departement_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Enseignant -->
                            <div class="col-md-4 mb-3">
                                <label for="enseignant_id" class="form-label">
                                    Enseignant <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('enseignant_id') is-invalid @enderror" 
                                        id="enseignant_id" 
                                        name="enseignant_id" 
                                        required>
                                    <option value="">Sélectionner un enseignant</option>
                                    @foreach($enseignants as $id => $nom)
                                        <option value="{{ $id }}" 
                                            {{ old('enseignant_id', $cours->enseignant_id) == $id ? 'selected' : '' }}>
                                            {{ $nom }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('enseignant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Crédits -->
                            <div class="col-md-4 mb-3">
                                <label for="credits" class="form-label">
                                    Crédits <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('credits') is-invalid @enderror" 
                                        id="credits" 
                                        name="credits" 
                                        required>
                                    <option value="">Sélectionner</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" 
                                            {{ old('credits', $cours->credits) == $i ? 'selected' : '' }}>
                                            {{ $i }} crédit{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('credits')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Semestre -->
                            <div class="col-md-4 mb-3">
                                <label for="semestre" class="form-label">
                                    Semestre
                                </label>
                                <select class="form-select" id="semestre" name="semestre">
                                    <option value="">Sélectionner</option>
                                    <option value="1" {{ old('semestre', $cours->semestre) == '1' ? 'selected' : '' }}>Semestre 1</option>
                                    <option value="2" {{ old('semestre', $cours->semestre) == '2' ? 'selected' : '' }}>Semestre 2</option>
                                    <option value="annuel" {{ old('semestre', $cours->semestre) == 'annuel' ? 'selected' : '' }}>Annuel</option>
                                </select>
                            </div>

                            <!-- Niveau -->
                            <div class="col-md-4 mb-3">
                                <label for="niveau" class="form-label">
                                    Niveau
                                </label>
                                <select class="form-select" id="niveau" name="niveau">
                                    <option value="">Sélectionner</option>
                                    <option value="L1" {{ old('niveau', $cours->niveau) == 'L1' ? 'selected' : '' }}>Licence 1</option>
                                    <option value="L2" {{ old('niveau', $cours->niveau) == 'L2' ? 'selected' : '' }}>Licence 2</option>
                                    <option value="L3" {{ old('niveau', $cours->niveau) == 'L3' ? 'selected' : '' }}>Licence 3</option>
                                    <option value="M1" {{ old('niveau', $cours->niveau) == 'M1' ? 'selected' : '' }}>Master 1</option>
                                    <option value="M2" {{ old('niveau', $cours->niveau) == 'M2' ? 'selected' : '' }}>Master 2</option>
                                </select>
                            </div>

                            <!-- Langue d'enseignement -->
                            <div class="col-md-4 mb-3">
                                <label for="langue" class="form-label">
                                    Langue d'enseignement
                                </label>
                                <select class="form-select" id="langue" name="langue">
                                    <option value="">Sélectionner</option>
                                    <option value="fr" {{ old('langue', $cours->langue) == 'fr' ? 'selected' : '' }}>Français</option>
                                    <option value="en" {{ old('langue', $cours->langue) == 'en' ? 'selected' : '' }}>Anglais</option>
                                    <option value="bi" {{ old('langue', $cours->langue) == 'bi' ? 'selected' : '' }}>Kirundi</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Description -->
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">
                                    Description du cours
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="4">{{ old('description', $cours->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Objectifs d'apprentissage -->
                            <div class="col-md-6 mb-3">
                                <label for="objectifs" class="form-label">
                                    Objectifs d'apprentissage
                                </label>
                                <textarea class="form-control" 
                                          id="objectifs" 
                                          name="objectifs" 
                                          rows="3">{{ old('objectifs', $cours->objectifs) }}</textarea>
                            </div>

                            <!-- Prérequis -->
                            <div class="col-md-6 mb-3">
                                <label for="prerequis" class="form-label">
                                    Prérequis
                                </label>
                                <textarea class="form-control" 
                                          id="prerequis" 
                                          name="prerequis" 
                                          rows="3">{{ old('prerequis', $cours->prerequis) }}</textarea>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Bibliographie -->
                            <div class="col-md-12 mb-3">
                                <label for="bibliographie" class="form-label">
                                    Bibliographie / Références
                                </label>
                                <textarea class="form-control" 
                                          id="bibliographie" 
                                          name="bibliographie" 
                                          rows="3">{{ old('bibliographie', $cours->bibliographie) }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Options supplémentaires -->
                        <h6 class="mb-3">Options</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="obligatoire" 
                                           name="obligatoire" 
                                           value="1"
                                           {{ old('obligatoire', $cours->obligatoire) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="obligatoire">
                                        Cours obligatoire
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="avec_td" 
                                           name="avec_td" 
                                           value="1"
                                           {{ old('avec_td', $cours->avec_td) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="avec_td">
                                        Avec travaux dirigés
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="avec_tp" 
                                           name="avec_tp" 
                                           value="1"
                                           {{ old('avec_tp', $cours->avec_tp) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="avec_tp">
                                        Avec travaux pratiques
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="examen_final" 
                                           name="examen_final" 
                                           value="1"
                                           {{ old('examen_final', $cours->examen_final) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="examen_final">
                                        Examen final
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Horaires suggérés -->
                            <div class="col-md-4 mb-3">
                                <label for="heures_cours" class="form-label">
                                    Heures de cours magistral
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="heures_cours" 
                                       name="heures_cours" 
                                       value="{{ old('heures_cours', $cours->heures_cours) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="heures_td" class="form-label">
                                    Heures de TD
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="heures_td" 
                                       name="heures_td" 
                                       value="{{ old('heures_td', $cours->heures_td) }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="heures_tp" class="form-label">
                                    Heures de TP
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="heures_tp" 
                                       name="heures_tp" 
                                       value="{{ old('heures_tp', $cours->heures_tp) }}">
                            </div>
                        </div>

                        <!-- Statistiques (informatives) -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Informations sur le cours</h6>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small class="text-muted">Étudiants inscrits:</small>
                                                <br>
                                                <strong>{{ $cours->inscriptions?->count() ?? 0 }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Notes saisies:</small>
                                                <br>
                                                <strong>{{ $cours->notes?->count() ?? 0 }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Moyenne du cours:</small>
                                                <br>
                                                @php
                                                    $moyenne = $cours->notes?->avg('note') ?? 0;
                                                @endphp
                                                <strong>{{ $moyenne > 0 ? number_format($moyenne, 2) : 'N/A' }}/20</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">Date création:</small>
                                                <br>
                                                <strong>{{ $cours->created_at?->format('d/m/Y') ?? 'N/A' }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i> Mettre à jour
                                </button>
                                <a href="{{ route('admin.cours.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <a href="{{ route('admin.cours.show', $cours->id) }}" 
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
    // Validation du formulaire
    document.getElementById('coursForm').addEventListener('submit', function(e) {
        const nom = document.getElementById('nom_cours').value;
        
        // Validation du nom
        if (nom.length < 5) {
            e.preventDefault();
            alert('Le nom du cours doit contenir au moins 5 caractères');
            return false;
        }
    });

    // Confirmation si modification majeure
    const originalData = {
        departement: document.getElementById('departement_id').value,
        enseignant: document.getElementById('enseignant_id').value,
        credits: document.getElementById('credits').value
    };
    
    document.getElementById('coursForm').addEventListener('submit', function(e) {
        const newData = {
            departement: document.getElementById('departement_id').value,
            enseignant: document.getElementById('enseignant_id').value,
            credits: document.getElementById('credits').value
        };
        
        if (JSON.stringify(originalData) !== JSON.stringify(newData)) {
            if (!confirm('Vous avez modifié des informations importantes. Les étudiants déjà inscrits seront affectés. Continuer?')) {
                e.preventDefault();
            }
        }
    });
</script>
@endpush
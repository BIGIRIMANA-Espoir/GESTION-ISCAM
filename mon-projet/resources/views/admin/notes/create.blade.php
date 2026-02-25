@extends('layouts.dashboard')

@section('title', 'Saisie des Notes')
@section('page-title', 'Nouvelle Note')

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
                            <i class="fas fa-pen text-primary me-2"></i>
                            @if(request()->has('cours_id'))
                                Saisie groupée - {{ $cours->nom_cours ?? '' }}
                            @else
                                Saisie individuelle
                            @endif
                        </h5>
                        <div>
                            <a href="{{ route('admin.notes.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Choix du mode de saisie -->
                    @if(!request()->has('cours_id'))
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user-edit fa-3x text-primary mb-3"></i>
                                        <h5>Saisie individuelle</h5>
                                        <p class="text-muted">Saisir une note pour un étudiant spécifique</p>
                                        <a href="#individuelle" class="btn btn-outline-primary" data-bs-toggle="collapse">
                                            Choisir
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-3x text-success mb-3"></i>
                                        <h5>Saisie groupée</h5>
                                        <p class="text-muted">Saisir les notes pour tout un cours</p>
                                        <a href="#groupee" class="btn btn-outline-success" data-bs-toggle="collapse">
                                            Choisir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Formulaire saisie individuelle -->
                    <div class="collapse {{ !request()->has('cours_id') ? 'show' : '' }}" id="individuelle">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Saisie individuelle
                                </h6>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.notes.store') }}" method="POST">
                                    @csrf
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="etudiant_id" class="form-label">
                                                Étudiant <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="etudiant_id" name="etudiant_id" required>
                                                <option value="">Sélectionner un étudiant</option>
                                                @foreach($etudiants as $etudiant)
                                                    <option value="{{ $etudiant->id }}">
                                                        {{ $etudiant->prenom ?? '' }} {{ $etudiant->nom ?? '' }} ({{ $etudiant->matricule ?? '' }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="cours_id" class="form-label">
                                                Cours <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="cours_id" name="cours_id" required>
                                                <option value="">Sélectionner un cours</option>
                                                @foreach($coursList as $cours)
                                                    <option value="{{ $cours->id }}" 
                                                        {{ request('cours_id') == $cours->id ? 'selected' : '' }}>
                                                        {{ $cours->code_cours ?? '' }} - {{ $cours->nom_cours ?? '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="note" class="form-label">
                                                Note <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" 
                                                   class="form-control" 
                                                   id="note" 
                                                   name="note" 
                                                   step="0.01" 
                                                   min="0" 
                                                   max="20" 
                                                   required>
                                            <small class="text-muted">Note sur 20</small>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="session" class="form-label">
                                                Session
                                            </label>
                                            <select class="form-select" id="session" name="session">
                                                <option value="normale">Normale</option>
                                                <option value="rattrapage">Rattrapage</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="annee_academique" class="form-label">
                                                Année académique <span class="text-danger">*</span>
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

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Enregistrer la note
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Formulaire saisie groupée -->
                    <div class="collapse {{ request()->has('cours_id') ? 'show' : '' }}" id="groupee">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-users me-2"></i>
                                    Saisie groupée
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(!request()->has('cours_id'))
                                    <form action="{{ route('admin.notes.create') }}" method="GET">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="select_cours" class="form-label">
                                                    Choisir un cours
                                                </label>
                                                <select class="form-select" id="select_cours" name="cours_id" required>
                                                    <option value="">Sélectionner...</option>
                                                    @foreach($coursList as $cours)
                                                        <option value="{{ $cours->id }}">
                                                            {{ $cours->code_cours ?? '' }} - {{ $cours->nom_cours ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="select_session" class="form-label">
                                                    Session
                                                </label>
                                                <select class="form-select" id="select_session" name="session">
                                                    <option value="normale">Normale</option>
                                                    <option value="rattrapage">Rattrapage</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-arrow-right me-1"></i> Continuer
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.notes.bulk-store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cours_id" value="{{ $cours->id ?? '' }}">
                                        <input type="hidden" name="session" value="{{ request('session', 'normale') }}">
                                        <input type="hidden" name="annee_academique" value="{{ $anneeEnCours ?? '2025-2026' }}">
                                        
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Saisie des notes pour <strong>{{ $cours->nom_cours ?? '' }}</strong> 
                                            ({{ $cours->code_cours ?? '' }}) - Session {{ request('session', 'normale') }}
                                            <br>
                                            <small>
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                Année académique: <strong>{{ $anneeEnCours ?? '2025-2026' }}</strong>
                                            </small>
                                        </div>
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Matricule</th>
                                                        <th>Étudiant</th>
                                                        <th>Note (sur 20)</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse(($etudiantsInscrits ?? []) as $etudiant)
                                                        @php
                                                            $noteExistante = isset($notesExistantes[$etudiant->id]) ? $notesExistantes[$etudiant->id] : null;
                                                        @endphp
                                                        <tr class="{{ $noteExistante ? 'table-warning' : '' }}">
                                                            <td>
                                                                <span class="badge bg-secondary">{{ $etudiant->matricule ?? '' }}</span>
                                                            </td>
                                                            <td>
                                                                {{ $etudiant->prenom ?? '' }} {{ $etudiant->nom ?? '' }}
                                                                @if($noteExistante)
                                                                    <br>
                                                                    <small class="text-warning">
                                                                        <i class="fas fa-exclamation-triangle"></i>
                                                                        Note déjà saisie: {{ $noteExistante->note ?? '' }}/20
                                                                    </small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <input type="number" 
                                                                       class="form-control form-control-sm" 
                                                                       name="notes[{{ $etudiant->id ?? '' }}]" 
                                                                       step="0.01" 
                                                                       min="0" 
                                                                       max="20"
                                                                       value="{{ $noteExistante->note ?? '' }}"
                                                                       placeholder="Note">
                                                            </td>
                                                            <td>
                                                                @if($noteExistante)
                                                                    <span class="badge bg-warning">Déjà saisie</span>
                                                                @else
                                                                    <span class="badge bg-success">À saisir</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center py-4">
                                                                <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
                                                                <p class="text-muted">Aucun étudiant inscrit à ce cours</p>
                                                                @if(isset($cours) && $cours)
                                                                    <a href="{{ route('admin.inscriptions.create', ['cours_id' => $cours->id]) }}" 
                                                                       class="btn btn-primary btn-sm">
                                                                        Inscrire des étudiants
                                                                    </a>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        @if(($etudiantsInscrits ?? collect([]))->count() > 0)
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="fillAll">
                                                        <i class="fas fa-magic me-1"></i> Remplir avec une note par défaut
                                                    </button>
                                                    
                                                    <div class="float-end">
                                                        <a href="{{ route('admin.notes.create') }}" class="btn btn-secondary">
                                                            <i class="fas fa-times me-1"></i> Annuler
                                                        </a>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-save me-1"></i> Enregistrer toutes les notes
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Select2 CSS - Retiré car on utilise les selects standards -->
@endpush

@push('scripts')
<script>
    // Remplir toutes les notes avec une valeur par défaut
    document.getElementById('fillAll')?.addEventListener('click', function() {
        const defaultNote = prompt('Entrez la note par défaut (0-20):', '10');
        if (defaultNote !== null && defaultNote >= 0 && defaultNote <= 20) {
            document.querySelectorAll('input[name^="notes["]').forEach(input => {
                if (!input.value) {
                    input.value = defaultNote;
                }
            });
        }
    });

    // Validation des notes avant soumission
    document.querySelector('form[action*="bulk-store"]')?.addEventListener('submit', function(e) {
        let hasErrors = false;
        let message = '';
        
        document.querySelectorAll('input[name^="notes["]').forEach(input => {
            if (input.value) {
                const note = parseFloat(input.value);
                if (isNaN(note) || note < 0 || note > 20) {
                    hasErrors = true;
                    message = 'Les notes doivent être comprises entre 0 et 20';
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            alert(message);
        } else {
            if (!confirm('Confirmer l\'enregistrement de toutes ces notes?')) {
                e.preventDefault();
            }
        }
    });
</script>
@endpush
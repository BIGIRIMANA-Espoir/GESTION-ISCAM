@extends('layouts.dashboard')

@section('title', 'Mon Profil')
@section('page-title', 'Mon Profil')

@section('menu')
    <a href="{{ route('etudiant.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('etudiant.notes.index') }}">
        <i class="fas fa-chart-line"></i> Mes notes
    </a>
    <a href="{{ route('etudiant.inscriptions.index') }}">
        <i class="fas fa-pen-to-square"></i> Mes inscriptions
    </a>
    <a href="{{ route('etudiant.profile') }}" class="active">
        <i class="fas fa-user"></i> Mon profil
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle text-primary me-2"></i>
                        Photo de profil
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-user-graduate fa-4x text-primary"></i>
                    </div>
                    <h5>{{ $etudiant->prenom }} {{ $etudiant->nom }}</h5>
                    <p class="text-muted mb-0">{{ $etudiant->matricule }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Modifier mes informations
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('etudiant.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Matricule</label>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $etudiant->matricule }}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom complet</label>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $etudiant->prenom }} {{ $etudiant->nom }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
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

                            <div class="col-md-6 mb-3">
                                <label for="adresse" class="form-label">Adresse</label>
                                <input type="text" 
                                       class="form-control @error('adresse') is-invalid @enderror" 
                                       id="adresse" 
                                       name="adresse" 
                                       value="{{ old('adresse', $etudiant->adresse) }}"
                                       placeholder="Votre adresse">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $etudiant->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $etudiant->date_naissance->format('d/m/Y') }}" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lieu de naissance</label>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $etudiant->lieu_naissance }}" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sexe</label>
                                <input type="text" class="form-control bg-light" 
                                       value="{{ $etudiant->sexe == 'M' ? 'Masculin' : 'Féminin' }}" readonly>
                            </div>
                        </div>

                        <hr>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
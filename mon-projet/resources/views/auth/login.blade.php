@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h4 class="mb-0">
                        <i class="fas fa-lock me-2"></i>
                        Connexion
                    </h4>
                </div>

                <div class="card-body p-4">
                    {{-- Ajout de autocomplete="off" sur le formulaire pour aider au nettoyage --}}
                    <form method="POST" action="{{ route('login') }}" autocomplete="off">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                Adresse email
                            </label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="" {{-- On force la valeur vide --}}
                                   required 
                                   autocomplete="new-password" {{-- NETTOYAGE : admin@iscam.bi supprimé --}}
                                   placeholder="exemple@iscam.bi">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-key me-2 text-primary"></i>
                                Mot de passe
                            </label>
                            <input id="password" 
                                   type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   name="password" 
                                   required 
                                   autocomplete="new-password" {{-- NETTOYAGE : étoiles ******** supprimées --}}
                                   placeholder="••••••••">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">
                                    Se souvenir de moi
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Se connecter
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a class="text-muted" href="{{ route('password.request') }}">
                                    <small>Mot de passe oublié ?</small>
                                </a>
                            </div>
                        @endif
                        
                        <hr class="my-4">
                        
                        <div class="text-center">
                            <p class="text-muted mb-0">
                                Pas encore inscrit ?
                                <a href="{{ route('register') }}" class="text-primary fw-bold">
                                    Faire une demande
                                </a>
                            </p>
                        </div>

                        <!-- LIEN RETOUR VERS ACCUEIL -->
                        <div class="text-center mt-3">
                            <a href="{{ url('/') }}" class="text-muted">
                                <i class="fas fa-arrow-left me-1"></i> Retour à l'accueil
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Accueil - ISCAM')

@section('content')
<div class="container">
    <!-- Bannière d'accueil avec logo amélioré -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-primary text-white p-5 rounded-3">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <!-- Logo détaillé -->
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <div class="mb-2">
                                    <i class="fas fa-shield-alt fa-2x text-warning mx-1"></i>
                                    <i class="fas fa-shield-alt fa-2x text-warning mx-1"></i>
                                    <i class="fas fa-shield-alt fa-2x text-warning mx-1"></i>
                                </div>
                                <div>
                                    <i class="fas fa-crown fa-2x text-warning"></i>
                                    <i class="fas fa-lion fa-3x text-warning" style="margin-left: -10px;"></i>
                                </div>
                                <div class="mt-2">
                                    <small class="text-warning">UNITÉ-TRAVAIL-PROGRÈS</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <h1 class="display-4">
                            ISCAM Gestion Académique
                        </h1>
                        <p class="lead">
                            Institut Supérieur des Cadres Militaires
                        </p>
                        <p>
                            <small>Membres de la Force de Défense Nationale du BURUNDI (FDNB)</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques DYNAMIQUES -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h5>Étudiants</h5>
                    <h2 class="text-primary">{{ $stats['etudiants'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-chalkboard-teacher fa-3x text-success mb-3"></i>
                    <h5>Enseignants</h5>
                    <h2 class="text-success">{{ $stats['enseignants'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-book fa-3x text-warning mb-3"></i>
                    <h5>Cours</h5>
                    <h2 class="text-warning">{{ $stats['cours'] }}</h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-building fa-3x text-info mb-3"></i>
                    <h5>Départements</h5>
                    <h2 class="text-info">{{ $stats['departements'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications DYNAMIQUES -->
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-newspaper me-2 text-primary"></i>
                        Actualités de l'institut
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($notifications as $notif)
                    <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                        <div class="me-3">
                            @if($notif['type'] == 'success')
                                <i class="fas fa-check-circle text-success fa-lg"></i>
                            @elseif($notif['type'] == 'info')
                                <i class="fas fa-info-circle text-info fa-lg"></i>
                            @else
                                <i class="fas fa-exclamation-circle text-warning fa-lg"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0">{{ $notif['message'] }}</p>
                            <small class="text-muted">{{ $notif['time'] }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2 text-success"></i>
                        Événements à venir
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>15 Mars</strong>
                        <p class="mb-0">Début des Examens</p>
                    </div>
                    <div class="mb-3">
                        <strong>30 Mars</strong>
                        <p class="mb-0">Fin des Examens</p>
                    </div>
                    <div>
                        <strong>5 Avril</strong>
                        <p class="mb-0">Publication des Résultats</p>
                    </div>
                </div>
            </div>
            
            <!-- Connexion rapide si non connecté -->
            @guest
            <div class="card border-0 shadow mt-3">
                <div class="card-body text-center">
                    <p class="mb-3">Connectez-vous pour accéder à votre espace</p>
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>Se connecter
                    </a>
                </div>
            </div>
            @endguest
        </div>
    </div>
</div>
@endsection
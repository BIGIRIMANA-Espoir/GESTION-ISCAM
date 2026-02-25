@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tableau de Bord - ISCAM</h5>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="text-center">
                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                        <h4>Bienvenue, {{ auth()->user()->name }}!</h4>
                        <p class="text-muted">Vous êtes connecté avec succès.</p>
                        <p><small>Rôle: {{ auth()->user()->role ?? 'étudiant' }}</small></p>
                        
                        <hr>
                        
                        <p class="mt-3">
                            <a href="{{ url('/') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i>Retour à l'accueil
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
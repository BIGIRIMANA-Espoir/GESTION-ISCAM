@extends('layouts.dashboard')

@section('title', 'Mes Cours')
@section('page-title', 'Gestion des Notes')

@section('menu')
    <a href="{{ route('enseignant.dashboard') }}">
        <i class="fas fa-home"></i> Dashboard
    </a>
    <a href="{{ route('enseignant.cours.index') }}" class="active">
        <i class="fas fa-book"></i> Mes cours
    </a>
    <a href="{{ route('enseignant.notes.index') }}">
        <i class="fas fa-pen"></i> Saisie des notes
    </a>
    <a href="{{ route('enseignant.etudiants.index') }}">
        <i class="fas fa-user-graduate"></i> Mes étudiants
    </a>
    <a href="{{ route('enseignant.profile') }}">
        <i class="fas fa-user"></i> Mon profil
    </a>
@endsection

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h4 class="mb-0">
                        <i class="fas fa-book-open me-2"></i>
                        Mes cours - Année {{ $anneeCourante ?? now()->format('Y') }}
                    </h4>
                </div>
            </div> 
        </div>
    </div>

    <!-- Liste des cours -->
    <div class="row">
        @forelse($cours as $coursItem)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">{{ $coursItem->code_cours }}</span>
                        <span class="badge bg-info">{{ $coursItem->credits }} crédits</span>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $coursItem->nom_cours }}</h5>
                    
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <small class="text-muted">Département</small>
                            <br>
                            <span class="badge bg-secondary">{{ $coursItem->departement->nom_departement ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <small class="text-muted">Étudiants</small>
                            <br>
                            <span class="badge bg-info">{{ $coursItem->inscriptions?->count() ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <!-- Statistiques rapides -->
                    @php
                        $notes = $coursItem->notes;
                        $moyenne = $notes?->avg('note') ?? 0;
                        $totalNotes = $notes?->count() ?? 0;
                        $reussite = $notes?->where('note', '>=', 10)->count() ?? 0;
                    @endphp
                    
                    @if($totalNotes > 0)
                    <div class="mb-3">
                        <small class="text-muted">Progression des notes</small>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar bg-success" 
                                 style="width: {{ ($reussite/$totalNotes)*100 }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small>{{ $reussite }} réussite(s)</small>
                            <small>{{ number_format($moyenne, 2) }}/20</small>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-white border-0 pb-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('enseignant.notes.create', $coursItem->id) }}" 
                           class="btn btn-success">
                            <i class="fas fa-pen me-2"></i> Saisir les notes
                        </a>
                        <a href="{{ route('enseignant.notes.show', $coursItem->id) }}" 
                           class="btn btn-outline-primary">
                            <i class="fas fa-eye me-2"></i> Voir les notes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h5>Aucun cours trouvé</h5>
                <p class="text-muted">Vous n'avez pas encore de cours assignés.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>
@endsection
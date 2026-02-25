<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;  // ← IMPORTANT !

// ===========================================
// ROUTES PUBLIQUES
// ===========================================
Route::get('/', [DashboardController::class, 'index'])->name('accueil');

// ===========================================
// AUTHENTIFICATION (Laravel UI)
// ===========================================
Auth::routes();

// ===========================================
// ROUTES PROTÉGÉES PAR AUTH
// ===========================================
Route::middleware(['auth'])->group(function () {
    
    // Home après connexion
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    // ===========================================
    // ROUTES ADMIN (Middleware rôle admin)
    // ===========================================
    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        
        // Statistiques
        Route::get('/statistiques', [App\Http\Controllers\Admin\StatistiqueController::class, 'index'])->name('statistiques.index');
        Route::get('/statistiques/cours', [App\Http\Controllers\Admin\StatistiqueController::class, 'byCourse'])->name('statistiques.cours');
        Route::get('/statistiques/etudiants', [App\Http\Controllers\Admin\StatistiqueController::class, 'byStudent'])->name('statistiques.etudiants');
        Route::get('/statistiques/departements', [App\Http\Controllers\Admin\StatistiqueController::class, 'byDepartment'])->name('statistiques.departements');
        
        // CRUD Étudiants
        Route::resource('etudiants', App\Http\Controllers\Admin\EtudiantController::class);
        
        // CRUD Enseignants
        Route::resource('enseignants', App\Http\Controllers\Admin\EnseignantController::class);
        
        // CRUD Cours
        Route::resource('cours', App\Http\Controllers\Admin\CoursController::class);
        
        // CRUD Inscriptions
        Route::resource('inscriptions', App\Http\Controllers\Admin\InscriptionController::class);
        Route::put('/inscriptions/{id}/approuver', [App\Http\Controllers\Admin\InscriptionController::class, 'approuver'])->name('inscriptions.approuver');
        Route::put('/inscriptions/{id}/reject', [App\Http\Controllers\Admin\InscriptionController::class, 'reject'])->name('inscriptions.reject');
        
        // CRUD Notes
        Route::resource('notes', App\Http\Controllers\Admin\NoteController::class);
        Route::get('/notes/create-bulk', [App\Http\Controllers\Admin\NoteController::class, 'bulkCreate'])->name('notes.bulk-create');
        Route::post('/notes/bulk-store', [App\Http\Controllers\Admin\NoteController::class, 'bulkStore'])->name('notes.bulk-store');
        
        // ✅ AJOUT : Demandes d'inscription (pending-users)
        Route::get('/pending-users', [App\Http\Controllers\Admin\PendingUserController::class, 'index'])->name('pending-users.index');
        Route::get('/pending-users/{id}', [App\Http\Controllers\Admin\PendingUserController::class, 'show'])->name('pending-users.show');
        Route::post('/pending-users/{id}/approve', [App\Http\Controllers\Admin\PendingUserController::class, 'approve'])->name('pending-users.approve');
        Route::post('/pending-users/{id}/reject', [App\Http\Controllers\Admin\PendingUserController::class, 'reject'])->name('pending-users.reject');
    });
    
    // ===========================================
    // ROUTES ENSEIGNANT (Middleware rôle enseignant)
    // ===========================================
    Route::prefix('enseignant')->name('enseignant.')->middleware('role:enseignant')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Enseignant\DashboardController::class, 'index'])->name('dashboard');
        
        // Mes cours
        Route::get('/cours', [App\Http\Controllers\Enseignant\NoteController::class, 'index'])->name('cours.index');
        
        // Gestion des notes
        Route::get('/notes', [App\Http\Controllers\Enseignant\NoteController::class, 'index'])->name('notes.index');
        Route::get('/notes/create/{cours}', [App\Http\Controllers\Enseignant\NoteController::class, 'create'])->name('notes.create');
        Route::post('/notes', [App\Http\Controllers\Enseignant\NoteController::class, 'store'])->name('notes.store');
        Route::get('/notes/{note}/edit', [App\Http\Controllers\Enseignant\NoteController::class, 'edit'])->name('notes.edit');
        Route::put('/notes/{note}', [App\Http\Controllers\Enseignant\NoteController::class, 'update'])->name('notes.update');
        Route::get('/notes/{cours}', [App\Http\Controllers\Enseignant\NoteController::class, 'show'])->name('notes.show');
        
        // Mes étudiants
        Route::get('/etudiants', [App\Http\Controllers\Enseignant\EtudiantController::class, 'index'])->name('etudiants.index');
        Route::get('/etudiants/{id}', [App\Http\Controllers\Enseignant\EtudiantController::class, 'show'])->name('etudiants.show');
        
        // Profil
        Route::get('/profil', [App\Http\Controllers\Enseignant\ProfileController::class, 'index'])->name('profile');
        Route::put('/profil', [App\Http\Controllers\Enseignant\ProfileController::class, 'update'])->name('profile.update');
    });
    
    // ===========================================
    // ROUTES ÉTUDIANT (Middleware rôle étudiant)
    // ===========================================
    Route::prefix('etudiant')->name('etudiant.')->middleware('role:etudiant')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Etudiant\DashboardController::class, 'index'])->name('dashboard');
        
        // Mes notes
        Route::get('/notes', [App\Http\Controllers\Etudiant\NoteController::class, 'index'])->name('notes.index');
        Route::get('/notes/{id}', [App\Http\Controllers\Etudiant\NoteController::class, 'show'])->name('notes.show');
        Route::get('/releve', [App\Http\Controllers\Etudiant\NoteController::class, 'releve'])->name('notes.releve');
        
        // Mes inscriptions
        Route::resource('inscriptions', App\Http\Controllers\Etudiant\InscriptionController::class)->only([
            'index', 'create', 'store', 'show'
        ]);
        
        // Profil
        Route::get('/profil', [App\Http\Controllers\Etudiant\ProfileController::class, 'index'])->name('profile');
        Route::put('/profil', [App\Http\Controllers\Etudiant\ProfileController::class, 'update'])->name('profile.update');
    });
});
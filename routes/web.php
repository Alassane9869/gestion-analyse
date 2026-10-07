<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedecinPortalController;
use App\Http\Controllers\PatientPortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeAnalyseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', DashboardController::class)->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/resultats/{resultat}/bulletin', [\App\Http\Controllers\ResultatController::class, 'bulletin'])->name('resultats.bulletin');
    Route::get('/resultats/{resultat}/pdf', [\App\Http\Controllers\ResultatController::class, 'pdf'])->name('resultats.pdf');
});

Route::middleware(['auth', 'role:patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/profil', [PatientPortalController::class, 'profile'])->name('profil');
    Route::put('/profil', [PatientPortalController::class, 'updateProfile'])->name('profil.update');
    Route::get('/analyses', [PatientPortalController::class, 'analyses'])->name('analyses');
    Route::post('/analyses', [PatientPortalController::class, 'commander'])->name('analyses.commander');
    Route::get('/rendez-vous', [PatientPortalController::class, 'rendezVous'])->name('rendez-vous');
    Route::post('/rendez-vous', [PatientPortalController::class, 'prendreRendezVous'])->name('rendez-vous.store');
    Route::get('/resultats', [PatientPortalController::class, 'resultats'])->name('resultats');
});

Route::middleware(['auth', 'role:medecin'])->prefix('medecin')->name('medecin.')->group(function () {
    Route::get('/', fn () => redirect()->route('medecin.espace.dashboard'))->name('dashboard');
    Route::get('/types-analyses', [TypeAnalyseController::class, 'index'])->name('types.index');
    Route::post('/types-analyses', [TypeAnalyseController::class, 'store'])->name('types.store');
    Route::put('/types-analyses/{typeAnalyse}', [TypeAnalyseController::class, 'update'])->name('types.update');
    Route::delete('/types-analyses/{typeAnalyse}', [TypeAnalyseController::class, 'destroy'])->name('types.destroy');
    Route::get('/resultats', [MedecinPortalController::class, 'resultats'])->name('resultats');
    Route::get('/resultats/nouveau', [MedecinPortalController::class, 'creerResultat'])->name('resultats.create');
    Route::post('/resultats', [MedecinPortalController::class, 'enregistrerResultat'])->name('resultats.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

require __DIR__.'/auth.php';
require __DIR__.'/medecin.php';

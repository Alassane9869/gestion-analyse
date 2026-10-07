<?php

use App\Http\Controllers\Medecin\EspaceMedecinController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:medecin'])
    ->prefix('medecin')
    ->name('medecin.espace.')
    ->group(function () {
        Route::get('/dashboard', [EspaceMedecinController::class, 'dashboard'])->name('dashboard');
        Route::get('/patients', [EspaceMedecinController::class, 'patients'])->name('patients');
        Route::get('/patients/nouveau', [EspaceMedecinController::class, 'creerPatient'])->name('patients.create');
        Route::post('/patients', [EspaceMedecinController::class, 'enregistrerPatient'])->name('patients.store');
        Route::get('/patients/{patient}', [EspaceMedecinController::class, 'fichePatient'])->name('patients.show');
        Route::get('/patients/{patient}/modifier', [EspaceMedecinController::class, 'modifierPatient'])->name('patients.edit');
        Route::put('/patients/{patient}', [EspaceMedecinController::class, 'mettreAJourPatient'])->name('patients.update');
        Route::delete('/patients/{patient}', [EspaceMedecinController::class, 'supprimerPatient'])->name('patients.destroy');
        Route::get('/rendez-vous', [EspaceMedecinController::class, 'rendezVous'])->name('rendez-vous');
        Route::patch('/rendez-vous/{rendezVous}', [EspaceMedecinController::class, 'mettreAJourRendezVous'])->name('rendez-vous.update');
        Route::delete('/rendez-vous/{rendezVous}', [EspaceMedecinController::class, 'supprimerRendezVous'])->name('rendez-vous.destroy');
        Route::get('/analyses', [EspaceMedecinController::class, 'analyses'])->name('analyses');
        Route::patch('/analyses/{ligne}', [EspaceMedecinController::class, 'mettreAJourAnalyse'])->name('analyses.update');
    });
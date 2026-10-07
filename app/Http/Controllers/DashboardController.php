<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        $user = request()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isMedecin()) {
            return redirect()->route('medecin.espace.dashboard');
        }

        if ($user->isPatient()) {
            $patient = $user->patient;
            if (! $patient) {
                $patient = \App\Models\Patient::where('email', $user->email)->first();
                if ($patient) {
                    $patient->update(['user_id' => $user->id]);
                } else {
                    $parts = explode(' ', $user->name, 2);
                    $patient = \App\Models\Patient::create([
                        'user_id' => $user->id,
                        'prenom' => $parts[0] ?? $user->name,
                        'nom' => $parts[1] ?? 'Patient',
                        'email' => $user->email,
                    ]);
                }
            }

            $patient->load(['commandes.types', 'rendezVous.medecin', 'resultats.typeAnalyse']);

            return view('dashboard.patient', compact('patient'));
        }

        return redirect()->route('login')->withErrors(['role' => 'Votre profil utilisateur n’est pas configuré.']);
    }
}

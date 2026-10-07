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
            $patient = $user->patient()->with(['commandes.types', 'rendezVous.medecin', 'resultats.typeAnalyse'])->first();

            return view('dashboard.patient', compact('patient'));
        }

        return redirect()->route('login')->withErrors(['role' => 'Votre profil utilisateur n’est pas configuré.']);
    }
}

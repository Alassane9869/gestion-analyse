<?php

namespace App\Http\Controllers;

use App\Models\Medecin;
use App\Models\RendezVous;
use App\Models\Resultat;
use App\Models\TypeAnalyse;
use App\Notifications\ResultatValideNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedecinPortalController extends Controller
{
    public function dashboard(): RedirectResponse
    {
        return redirect()->route('medecin.espace.dashboard');
    }

    public function rendezVous(): View
    {
        return view('medecin.rendez-vous', ['rendezVous' => RendezVous::with(['patient', 'medecin'])->latest('date_heure')->get()]);
    }

    public function updateRendezVous(Request $request, RendezVous $rendezVous): RedirectResponse
    {
        $data = $request->validate(['statut' => ['required', 'in:accepte,refuse,en_attente']]);
        $rendezVous->update($data);

        return back()->with('success', 'Statut du rendez-vous mis à jour.');
    }

    public function supprimerRendezVous(RendezVous $rendezVous): RedirectResponse
    {
        $rendezVous->delete();

        return back()->with('success', 'Rendez-vous supprimé.');
    }

    public function resultats(): View
    {
        $resultats = Resultat::with(['patient', 'typeAnalyse', 'analyse'])->latest()->paginate(15);

        return view('medecin.resultats', compact('resultats'));
    }

    public function creerResultat(): View
    {
        return view('medecin.resultat-form', ['patients' => \App\Models\Patient::orderBy('nom')->get(), 'types' => TypeAnalyse::where('actif', true)->orderBy('nom')->get()]);
    }

    public function enregistrerResultat(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'type_analyse_id' => ['required', 'exists:type_analyses,id'],
            'date_resultat' => ['required', 'date'],
            'statut' => ['required', 'in:en_attente,en_cours,completee'],
            'valeur' => ['nullable', 'numeric'],
            'unite' => ['nullable', 'string', 'max:50'],
            'remarques' => ['nullable', 'string'],
        ]);
        $data['medecin_id'] = $this->medecin()->id;
        $resultat = Resultat::create($data);
        $resultat->load('patient');
        if ($resultat->statut === 'completee' && $resultat->patient?->whatsapp_opt_in && $resultat->patient?->user) {
            try {
                $resultat->patient->user->notify(new ResultatValideNotification($resultat));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('medecin.resultats')->with('success', 'Résultat enregistré.');
    }

    private function medecin(): Medecin
    {
        return request()->user()->medecin()->firstOrCreate([], [
            'nom' => request()->user()->name,
            'prenom' => '',
            'email' => request()->user()->email,
        ]);
    }
}

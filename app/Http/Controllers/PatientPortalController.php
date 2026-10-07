<?php

namespace App\Http\Controllers;

use App\Models\CommandeAnalyse;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\TypeAnalyse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PatientPortalController extends Controller
{
    public function profile(): View
    {
        return view('patient.profile', ['patient' => $this->patient()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $patient = $this->patient();
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'sexe' => ['nullable', 'string', 'max:20'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'adresse' => ['nullable', 'string', 'max:500'],
            'groupe_sanguin' => ['nullable', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'whatsapp_phone' => ['nullable', 'string', 'max:30', 'regex:/^\+?[0-9\s\-\.]{7,25}$/'],
        ]);

        $patient->update($data);

        $fullName = trim($data['prenom'] . ' ' . $data['nom']);
        if ($fullName !== '') {
            $request->user()->update(['name' => $fullName]);
        }

        return back()->with('success', 'Profil mis à jour.');
    }

    public function analyses(): View
    {
        return view('patient.analyses', ['types' => TypeAnalyse::where('actif', true)->orderBy('nom')->get()]);
    }

    public function commander(Request $request): RedirectResponse
    {
        $data = $request->validate(['types' => ['required', 'array', 'min:1'], 'types.*' => ['integer', 'exists:type_analyses,id']]);
        $types = TypeAnalyse::whereIn('id', $data['types'])->where('actif', true)->get();
        $patient = $this->patient();

        DB::transaction(function () use ($types, $patient): void {
            $commande = CommandeAnalyse::create(['patient_id' => $patient->id, 'total' => $types->sum('prix')]);
            $commande->types()->sync($types->mapWithKeys(fn (TypeAnalyse $type) => [$type->id => ['prix_unitaire' => $type->prix]])->all());
        });

        return back()->with('success', 'Votre sélection d’analyses a été enregistrée.');
    }

    public function rendezVous(): View
    {
        return view('patient.rendez-vous', ['medecins' => Medecin::orderBy('nom')->orderBy('prenom')->get(), 'rendezVous' => $this->patient()->rendezVous()->with('medecin')->latest()->get()]);
    }

    public function resultats(): View
    {
        $patient = $this->patient();
        $resultats = $patient->resultats()
            ->with(['typeAnalyse', 'analyse', 'medecin'])
            ->latest('date_resultat')
            ->latest('created_at')
            ->paginate(15);

        return view('patient.resultats', compact('resultats', 'patient'));
    }

    public function prendreRendezVous(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'medecin_id' => ['required', 'exists:medecins,id'],
            'date_heure' => ['required', 'date', 'after_or_equal:now'],
            'motif' => ['nullable', 'string', 'max:1000'],
        ]);

        $dateHeure = \Illuminate\Support\Carbon::parse($data['date_heure']);
        $debutFenetre = $dateHeure->copy()->subMinutes(15);
        $finFenetre = $dateHeure->copy()->addMinutes(15);

        // Vérification de conflit d'agenda chez le médecin
        $conflit = RendezVous::where('medecin_id', $data['medecin_id'])
            ->whereIn('statut', ['en_attente', 'accepte'])
            ->whereBetween('date_heure', [$debutFenetre, $finFenetre])
            ->exists();

        if ($conflit) {
            return back()->withErrors([
                'date_heure' => 'Ce créneau horaire est déjà réservé ou indisponible auprès de ce médecin. Veuillez choisir un autre créneau.',
            ])->withInput();
        }

        $data['patient_id'] = $this->patient()->id;
        $data['statut'] = 'en_attente';
        RendezVous::create($data);

        return back()->with('success', 'Rendez-vous demandé avec succès.');
    }

    private function patient(): Patient
    {
        return request()->user()->patient()->firstOrCreate([], [
            'nom' => request()->user()->name,
            'prenom' => '',
            'email' => request()->user()->email,
        ]);
    }
}

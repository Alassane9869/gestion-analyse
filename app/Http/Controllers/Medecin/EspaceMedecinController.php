<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medecin\StorePatientRequest;
use App\Http\Requests\Medecin\UpdateAnalyseCommandeRequest;
use App\Http\Requests\Medecin\UpdatePatientRequest;
use App\Http\Requests\Medecin\UpdateRendezVousStatusRequest;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\Resultat;
use App\Models\User;
use App\Notifications\RendezVousStatutNotification;
use App\Notifications\ResultatValideNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EspaceMedecinController extends Controller
{
    public function dashboard(): View
    {
        $user = request()->user();
        $isAdmin = $user->isAdmin();
        $medecin = $this->medecin();
        $analysesEnAttente = $this->analyseQuery()->limit(6)->get();
        $patientsRecents = Patient::latest()->limit(5)->get();
        $derniersResultats = Resultat::with(['patient', 'typeAnalyse'])->latest()->limit(5)->get();

        $rdvEnAttenteCount = $isAdmin 
            ? RendezVous::where('statut', 'en_attente')->count()
            : $medecin->rendezVous()->where('statut', 'en_attente')->count();

        $rdvDuJourCount = $isAdmin 
            ? RendezVous::whereDate('date_heure', today())->count()
            : $medecin->rendezVous()->whereDate('date_heure', today())->count();

        $prochainsRdvQuery = $isAdmin 
            ? RendezVous::query() 
            : $medecin->rendezVous();

        $prochainsRendezVous = $prochainsRdvQuery
            ->with(['patient', 'medecin'])
            ->where('date_heure', '>=', now())
            ->orderBy('date_heure')
            ->limit(5)
            ->get();

        return view('medecin.espace.dashboard', [
            'nombrePatients' => Patient::count(),
            'rendezVousEnAttente' => $rdvEnAttenteCount,
            'rendezVousDuJour' => $rdvDuJourCount,
            'analysesEnAttenteCount' => DB::table('commande_type_analyse')->whereIn('statut', ['en_attente', 'en_cours'])->count(),
            'prochainsRendezVous' => $prochainsRendezVous,
            'analysesEnAttente' => $analysesEnAttente,
            'patientsRecents' => $patientsRecents,
            'derniersResultats' => $derniersResultats,
        ]);
    }

    public function patients(): View
    {
        $search = trim((string) request('search'));
        $patients = Patient::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('whatsapp_phone', 'like', "%{$search}%")
                        ->orWhere('groupe_sanguin', 'like', "%{$search}%");
                });
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->paginate(15)
            ->withQueryString();

        return view('medecin.espace.patients.index', compact('patients', 'search'));
    }

    public function creerPatient(): View
    {
        return view('medecin.espace.patients.form', ['patient' => new Patient(), 'edition' => false]);
    }

    public function enregistrerPatient(StorePatientRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data): void {
            $user = User::create([
                'name' => trim($data['prenom'].' '.$data['nom']),
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'patient',
            ]);

            $patient = Patient::where('email', $data['email'])->first();
            $patientData = [
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['whatsapp_phone'],
                'whatsapp_phone' => $data['whatsapp_phone'],
                'groupe_sanguin' => $data['groupe_sanguin'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'sexe' => $data['sexe'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'user_id' => $user->id,
            ];

            if ($patient) {
                $patient->update($patientData);
            } else {
                Patient::create($patientData);
            }
        });

        return redirect()->route('medecin.espace.patients')->with('success', 'Patient ajouté avec succès.');
    }

    public function fichePatient(Patient $patient): View
    {
        $patient->load([
            'rendezVous' => fn ($q) => $q->orderByDesc('date_heure'),
            'resultats' => fn ($q) => $q->with(['typeAnalyse', 'medecin'])->orderByDesc('date_resultat'),
            'commandes' => fn ($q) => $q->with('typesAnalyses')->orderByDesc('created_at'),
        ]);

        return view('medecin.espace.patients.show', compact('patient'));
    }

    public function modifierPatient(Patient $patient): View
    {
        return view('medecin.espace.patients.form', ['patient' => $patient, 'edition' => true]);
    }

    public function mettreAJourPatient(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $patient): void {
            $user = $patient->user ?? User::query()
                ->where('email', $patient->email)
                ->where('role', 'patient')
                ->first();
            if (! $user) {
                $user = User::create([
                    'name' => trim($data['prenom'].' '.$data['nom']),
                    'email' => $data['email'],
                    'password' => $data['password'] ?: str()->password(32),
                    'role' => 'patient',
                ]);
            } else {
                $user->name = trim($data['prenom'].' '.$data['nom']);
                $user->email = $data['email'];
                if ($data['password'] ?? false) {
                    $user->password = $data['password'];
                }
                $user->save();
            }

            $patient->fill([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'telephone' => $data['whatsapp_phone'],
                'whatsapp_phone' => $data['whatsapp_phone'],
                'groupe_sanguin' => $data['groupe_sanguin'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'sexe' => $data['sexe'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'user_id' => $user->id,
            ])->save();
        });

        return redirect()->route('medecin.espace.patients')->with('success', 'Patient mis à jour avec succès.');
    }

    public function supprimerPatient(Patient $patient): RedirectResponse
    {
        DB::transaction(function () use ($patient): void {
            $patient->user?->delete();
            $patient->delete();
        });

        return redirect()->route('medecin.espace.patients')->with('success', 'Patient supprimé avec succès.');
    }

    public function rendezVous(): View
    {
        $user = request()->user();
        $query = $user->isAdmin() ? RendezVous::query() : $this->medecin()->rendezVous();

        $rendezVous = $query
            ->with(['patient', 'medecin'])
            ->orderByDesc('date_heure')
            ->paginate(15)
            ->withQueryString();

        return view('medecin.espace.rendez-vous', compact('rendezVous'));
    }

    public function mettreAJourRendezVous(UpdateRendezVousStatusRequest $request, RendezVous $rendezVous): RedirectResponse
    {
        if (! request()->user()->isAdmin()) {
            $this->medecin()->rendezVous()->whereKey($rendezVous->getKey())->firstOrFail();
        }
        $rendezVous->update($request->validated());

        // Notifier le patient par email de la mise à jour de son RDV
        try {
            $patient = $rendezVous->patient;
            if ($patient && $patient->user) {
                $patient->user->notify(new RendezVousStatutNotification($rendezVous));
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Statut du rendez-vous mis à jour et patient notifié.');
    }

    public function supprimerRendezVous(RendezVous $rendezVous): RedirectResponse
    {
        if (! request()->user()->isAdmin()) {
            $this->medecin()->rendezVous()->whereKey($rendezVous->getKey())->firstOrFail();
        }
        $rendezVous->delete();

        return back()->with('success', 'Rendez-vous supprimé.');
    }

    public function analyses(): View
    {
        $statut = request('statut', 'a_traiter');
        $analyses = $this->analyseQuery($statut)
            ->when(request()->filled('search'), function ($query): void {
                $search = trim((string) request('search'));
                $query->where(function ($query) use ($search): void {
                    $query->where('p.nom', 'like', "%{$search}%")
                        ->orWhere('p.prenom', 'like', "%{$search}%")
                        ->orWhere('p.email', 'like', "%{$search}%")
                        ->orWhere('ta.nom', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('ca.created_at')
            ->paginate(15)
            ->withQueryString();

        return view('medecin.espace.analyses', [
            'analyses' => $analyses,
            'search' => trim((string) request('search')),
            'statut' => $statut,
        ]);
    }

    public function mettreAJourAnalyse(UpdateAnalyseCommandeRequest $request, int $ligne): RedirectResponse
    {
        $data = $request->validated();
        $item = DB::table('commande_type_analyse as cta')
            ->join('commande_analyses as ca', 'ca.id', '=', 'cta.commande_analyse_id')
            ->join('type_analyses as ta', 'ta.id', '=', 'cta.type_analyse_id')
            ->where('cta.id', $ligne)
            ->select('cta.id', 'ca.patient_id', 'cta.type_analyse_id', 'ta.unite')
            ->first();

        abort_unless((bool) $item, 404);

        $savedResultat = null;

        DB::transaction(function () use ($data, $item, &$savedResultat): void {
            DB::table('commande_type_analyse')->where('id', $item->id)->update(['statut' => $data['statut']]);
            $resultat = Resultat::where('commande_type_analyse_id', $item->id)->first();

            if ($data['statut'] !== 'completee') {
                $resultat?->delete();

                return;
            }

            $resultat ??= new Resultat();
            $resultat->forceFill([
                'commande_type_analyse_id' => $item->id,
                'patient_id' => $item->patient_id,
                'analyse_id' => null,
                'type_analyse_id' => $item->type_analyse_id,
                'medecin_id' => $this->medecin()->id,
                'valeur' => $data['valeur'],
                'unite' => $data['unite'] ?? $item->unite,
                'date_resultat' => today(),
                'statut' => 'completee',
                'remarques' => $data['remarques'] ?? null,
            ])->save();

            $savedResultat = $resultat;
        });

        // Envoi automatique d'email / notification au patient si l'analyse est validée
        if ($data['statut'] === 'completee' && $savedResultat) {
            try {
                $savedResultat->load(['patient.user', 'typeAnalyse', 'medecin']);
                if ($savedResultat->patient?->user) {
                    $savedResultat->patient->user->notify(new ResultatValideNotification($savedResultat));
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return back()->with('success', 'Statut et résultat de l’analyse mis à jour. Patient notifié.');
    }

    private function analyseQuery(?string $statut = null)
    {
        $query = DB::table('commande_type_analyse as cta')
            ->join('commande_analyses as ca', 'ca.id', '=', 'cta.commande_analyse_id')
            ->join('patients as p', 'p.id', '=', 'ca.patient_id')
            ->join('type_analyses as ta', 'ta.id', '=', 'cta.type_analyse_id')
            ->leftJoin('resultats as r', 'r.commande_type_analyse_id', '=', 'cta.id');

        if ($statut === 'completee') {
            $query->where('cta.statut', 'completee');
        } elseif ($statut === 'en_cours') {
            $query->where('cta.statut', 'en_cours');
        } elseif ($statut === 'en_attente') {
            $query->where('cta.statut', 'en_attente');
        } elseif ($statut === 'tous') {
            // Pas de filtre de statut
        } else {
            // Par défaut: celles à traiter
            $query->whereIn('cta.statut', ['en_attente', 'en_cours']);
        }

        return $query
            ->orderByDesc('ca.created_at')
            ->select([
                'cta.id as ligne_id', 'ca.id as commande_id', 'ca.patient_id',
                'p.nom as patient_nom', 'p.prenom as patient_prenom', 'p.email as patient_email',
                'ta.nom as analyse_nom', 'ta.unite as unite_analyse', 'cta.prix_unitaire',
                'cta.statut', 'ca.created_at as date_demande', 'r.id as resultat_id', 'r.valeur', 'r.unite as unite_resultat',
                'r.remarques',
            ]);
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
<?php
namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\Resultat;
use App\Services\WhatsAppResultatService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class ResultatController extends Controller
{
    public function index(): View
    {
        $resultats = Resultat::with(['patient', 'analyse', 'medecin'])->get();

        return view('resultats.index', compact('resultats'));
    }

    public function create(): View
    {
        $patients = Patient::all();
        $analyses = Analyse::all();
        $medecins = Medecin::all();

        return view('resultats.create', compact('patients', 'analyses', 'medecins'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'analyse_id' => ['required', 'exists:analyses,id'],
            'medecin_id' => ['nullable', 'exists:medecins,id'],
            'valeur' => ['nullable', 'numeric'],
            'unite' => ['nullable', 'string', 'max:50'],
            'date_resultat' => ['nullable', 'date'],
            'statut' => ['nullable', 'string', 'max:50', 'in:en_attente,normal,conforme,positif,anormal'],
            'remarques' => ['nullable', 'string'],
        ]);

        $validated['statut'] ??= 'en_attente';

        Resultat::create($validated);

        return redirect()->route('resultats.index')->with('success', 'Résultat créé avec succès.');
    }

    public function show(Resultat $resultat): View
    {
        $resultat->load(['patient', 'analyse', 'medecin']);

        return view('resultats.show', compact('resultat'));
    }

    public function edit(Resultat $resultat): View
    {
        $patients = Patient::all();
        $analyses = Analyse::all();
        $medecins = Medecin::all();

        return view('resultats.edit', compact('resultat', 'patients', 'analyses', 'medecins'));
    }

    public function update(Request $request, Resultat $resultat): RedirectResponse
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'analyse_id' => ['required', 'exists:analyses,id'],
            'medecin_id' => ['nullable', 'exists:medecins,id'],
            'valeur' => ['nullable', 'numeric'],
            'unite' => ['nullable', 'string', 'max:50'],
            'date_resultat' => ['nullable', 'date'],
            'statut' => ['nullable', 'string', 'max:50', 'in:en_attente,normal,conforme,positif,anormal'],
            'remarques' => ['nullable', 'string'],
        ]);

        $resultat->update($validated);
        $resultat->update([
            'whatsapp_sent_at' => null,
            'whatsapp_message_id' => null,
            'whatsapp_error' => null,
        ]);

        return redirect()->route('resultats.index')->with('success', 'Résultat mis à jour avec succès.');
    }

    public function destroy(Resultat $resultat): RedirectResponse
    {
        $resultat->delete();

        return redirect()->route('resultats.index')->with('success', 'Résultat supprimé avec succès.');
    }

    public function sendWhatsApp(Resultat $resultat, WhatsAppResultatService $whatsApp): RedirectResponse
    {
        $resultat->load(['patient', 'analyse']);

        try {
            $messageId = $whatsApp->send($resultat);
            $resultat->update([
                'whatsapp_sent_at' => now(),
                'whatsapp_message_id' => $messageId,
                'whatsapp_error' => null,
            ]);

            return back()->with('success', 'Résultat envoyé au patient par WhatsApp.');
        } catch (Throwable $exception) {
            Log::warning('Échec de l’envoi WhatsApp du résultat.', [
                'resultat_id' => $resultat->id,
                'reason' => $exception->getMessage(),
            ]);
            $resultat->update(['whatsapp_error' => 'Envoi impossible. Vérifiez le consentement et la configuration WhatsApp.']);

            return back()->withErrors(['whatsapp' => 'Le résultat n’a pas pu être envoyé sur WhatsApp.']);
        }
    }

    public function bulletin(Resultat $resultat, \App\Services\PdfBulletinService $pdfService): View
    {
        $this->autoriserAccesBulletin($resultat);

        $donnees = $pdfService->preparerDonnees($resultat);

        return view('resultats.bulletin', $donnees);
    }

    public function pdf(Resultat $resultat, \App\Services\PdfBulletinService $pdfService)
    {
        $this->autoriserAccesBulletin($resultat);

        $donnees = $pdfService->preparerDonnees($resultat);
        $pdf = $pdfService->genererPdf($resultat);
        $nomFichier = sprintf('Bulletin_BioSante_%s.pdf', $donnees['referenceDossier']);

        if (request()->has('download')) {
            return $pdf->download($nomFichier);
        }

        return $pdf->stream($nomFichier);
    }

    protected function autoriserAccesBulletin(Resultat $resultat): void
    {
        $user = request()->user();

        if (! $user) {
            abort(403, 'Accès non autorisé.');
        }

        if ($user->isAdmin()) {
            return;
        }

        if ($user->isPatient()) {
            $patient = $user->patient;
            abort_unless($patient && $patient->id === $resultat->patient_id, 403, 'Vous n’avez pas l’autorisation d’accéder à ce bulletin.');
            return;
        }

        if (! $user->isMedecin()) {
            abort(403, 'Accès non autorisé.');
        }
    }
}


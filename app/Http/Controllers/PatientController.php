<?php
namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Laboratoire;
use App\Models\Patient;
use Illuminate\Support\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(): View
    {
        $patients = Patient::with('laboratoire')->get();

        return view('patients.index', compact('patients'));
    }

    public function create(): View
    {
        $laboratoires = Laboratoire::all();
        $analyses = Analyse::orderBy('nom')->get();

        return view('patients.create', compact('laboratoires', 'analyses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:patients,email'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'whatsapp_phone' => ['nullable', 'required_if:whatsapp_opt_in,1', 'regex:/^\+[1-9][0-9]{7,14}$/'],
            'whatsapp_opt_in' => ['nullable', 'boolean'],
            'date_naissance' => ['nullable', 'date'],
            'sexe' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string'],
            'laboratoire_id' => ['nullable', 'exists:laboratoires,id'],
            'analyses' => ['nullable', 'array'],
            'analyses.*' => ['integer', 'exists:analyses,id'],
        ]);

        $validated['whatsapp_opt_in'] = $request->boolean('whatsapp_opt_in');
        $validated['whatsapp_opt_in_at'] = $validated['whatsapp_opt_in'] ? Carbon::now() : null;
        $patient = Patient::create(collect($validated)->except('analyses')->all());
        $patient->analyses()->sync($validated['analyses'] ?? []);

        return redirect()->route('patients.index')->with('success', 'Patient créé avec succès.');
    }

    public function show(Patient $patient): View
    {
        $patient->load('analyses');

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient): View
    {
        $laboratoires = Laboratoire::all();
        $analyses = Analyse::orderBy('nom')->get();
        $patient->load('analyses');

        return view('patients.edit', compact('patient', 'laboratoires', 'analyses'));
    }

    public function update(Request $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:patients,email,' . $patient->id],
            'telephone' => ['nullable', 'string', 'max:50'],
            'whatsapp_phone' => ['nullable', 'required_if:whatsapp_opt_in,1', 'regex:/^\+[1-9][0-9]{7,14}$/'],
            'whatsapp_opt_in' => ['nullable', 'boolean'],
            'date_naissance' => ['nullable', 'date'],
            'sexe' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string'],
            'laboratoire_id' => ['nullable', 'exists:laboratoires,id'],
            'analyses' => ['nullable', 'array'],
            'analyses.*' => ['integer', 'exists:analyses,id'],
        ]);

        $validated['whatsapp_opt_in'] = $request->boolean('whatsapp_opt_in');
        $validated['whatsapp_opt_in_at'] = $validated['whatsapp_opt_in']
            ? ($patient->whatsapp_opt_in_at ?? Carbon::now())
            : null;
        $patient->update(collect($validated)->except('analyses')->all());
        $patient->analyses()->sync($validated['analyses'] ?? []);

        return redirect()->route('patients.index')->with('success', 'Patient mis à jour avec succès.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient supprimé avec succès.');
    }
}


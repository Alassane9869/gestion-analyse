<?php
namespace App\Http\Controllers;

use App\Models\Medecin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedecinController extends Controller
{
    public function index(): View
    {
        $medecins = Medecin::all();

        return view('medecins.index', compact('medecins'));
    }

    public function create(): View
    {
        return view('medecins.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:medecins,email'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'adresse' => ['nullable', 'string'],
        ]);

        Medecin::create($validated);

        return redirect()->route('medecins.index')->with('success', 'Médecin créé avec succès.');
    }

    public function show(Medecin $medecin): View
    {
        return view('medecins.show', compact('medecin'));
    }

    public function edit(Medecin $medecin): View
    {
        return view('medecins.edit', compact('medecin'));
    }

    public function update(Request $request, Medecin $medecin): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:medecins,email,' . $medecin->id],
            'telephone' => ['nullable', 'string', 'max:50'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'adresse' => ['nullable', 'string'],
        ]);

        $medecin->update($validated);

        return redirect()->route('medecins.index')->with('success', 'Médecin mis à jour avec succès.');
    }

    public function destroy(Medecin $medecin): RedirectResponse
    {
        $medecin->delete();

        return redirect()->route('medecins.index')->with('success', 'Médecin supprimé avec succès.');
    }
}


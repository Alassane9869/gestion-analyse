<?php
namespace App\Http\Controllers;

use App\Models\Analyse;
use App\Models\Laboratoire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyseController extends Controller
{
    public function index(): View
    {
        $analyses = Analyse::with('laboratoire')->get();

        return view('analyses.index', compact('analyses'));
    }

    public function create(): View
    {
        $laboratoires = Laboratoire::all();

        return view('analyses.create', compact('laboratoires'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:analyses,code'],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unite' => ['nullable', 'string', 'max:50'],
            'prix' => ['nullable', 'numeric', 'min:0'],
            'duree_minute' => ['nullable', 'integer', 'min:1'],
            'laboratoire_id' => ['nullable', 'exists:laboratoires,id'],
        ]);

        Analyse::create($validated);

        return redirect()->route('analyses.index')->with('success', 'Analyse créée avec succès.');
    }

    public function show(Analyse $analyse): View
    {
        return view('analyses.show', compact('analyse'));
    }

    public function edit(Analyse $analyse): View
    {
        $laboratoires = Laboratoire::all();

        return view('analyses.edit', compact('analyse', 'laboratoires'));
    }

    public function update(Request $request, Analyse $analyse): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:analyses,code,' . $analyse->id],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'unite' => ['nullable', 'string', 'max:50'],
            'prix' => ['nullable', 'numeric', 'min:0'],
            'duree_minute' => ['nullable', 'integer', 'min:1'],
            'laboratoire_id' => ['nullable', 'exists:laboratoires,id'],
        ]);

        $analyse->update($validated);

        return redirect()->route('analyses.index')->with('success', 'Analyse mise à jour avec succès.');
    }

    public function destroy(Analyse $analyse): RedirectResponse
    {
        $analyse->delete();

        return redirect()->route('analyses.index')->with('success', 'Analyse supprimée avec succès.');
    }
}


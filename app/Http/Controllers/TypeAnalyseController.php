<?php

namespace App\Http\Controllers;

use App\Models\TypeAnalyse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class TypeAnalyseController extends Controller
{
    public function index(): View
    {
        return view('medecin.types-analyses', ['types' => TypeAnalyse::orderBy('nom')->paginate(12)]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
                    'unite' => ['nullable', 'string', 'max:50'],
                    'duree_minute' => ['nullable', 'integer', 'min:1'],
        ]);
        $data['code'] = 'TA-' . Str::upper(Str::random(8));
        TypeAnalyse::create($data);

        return back()->with('success', 'Type d’analyse ajouté.');
    }

    public function update(Request $request, TypeAnalyse $typeAnalyse): RedirectResponse
    {
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'unite' => ['nullable', 'string', 'max:50'],
            'duree_minute' => ['nullable', 'integer', 'min:1'],
        ]);
        $data['actif'] = $request->boolean('actif');
        $typeAnalyse->update($data);

        return back()->with('success', 'Type d’analyse mis à jour.');
    }

    public function destroy(TypeAnalyse $typeAnalyse): RedirectResponse
    {
        if ($typeAnalyse->resultats()->exists() || $typeAnalyse->commandes()->exists()) {
            $typeAnalyse->update(['actif' => false]);

            return back()->with('success', 'Ce type d’analyse est lié à des dossiers médicaux : il a été désactivé du catalogue pour préserver l’historique.');
        }

        $typeAnalyse->delete();

        return back()->with('success', 'Type d’analyse supprimé.');
    }
}

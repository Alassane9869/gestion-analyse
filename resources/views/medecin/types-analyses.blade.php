@extends('layouts.medecin')
@section('title', 'Catalogue des Analyses')
@section('page-heading', 'Catalogue & Tarifs des Analyses')
@section('content')
<div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr]">
    <!-- Formulaire d'ajout dans le catalogue -->
    <section>
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Nouvel examen au catalogue</h3>
                    <p class="text-xs text-slate-500">Ajoutez un paramètre d’analyse et son tarif officiel.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('medecin.types.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="nom">Intitulé de l’analyse *</label>
                    <input id="nom" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                           name="nom" required placeholder="Ex: Hémogramme (NFS), Glycémie à jeun...">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="prix">Tarif en FCFA *</label>
                        <input id="prix" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500 font-bold" 
                               type="number" min="0" step="500" name="prix" required placeholder="Ex: 5000">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="unite">Unité de mesure</label>
                        <input id="unite" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                               name="unite" placeholder="Ex: g/L, mmol/L, %">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="description">Indications cliniques / Description</label>
                    <textarea id="description" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                              name="description" rows="3" placeholder="Conditions de prélèvement, à jeun, délai d’obtention..."></textarea>
                </div>

                <button class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-emerald-600 hover:bg-emerald-700 py-2.5 font-bold text-white text-xs shadow-sm transition" type="submit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Enregistrer dans le catalogue</span>
                </button>
            </form>
        </div>
    </section>

    <!-- Grille des analyses enregistrées -->
    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Analyses répertoriées</h3>
                <p class="text-xs text-slate-500">Tarification appliquée pour les patients.</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                {{ $types->total() }} examens
            </span>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            @forelse($types as $type)
                <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2">
                            <h4 class="font-bold text-slate-900 text-sm">{{ $type->nom }}</h4>
                            <span class="text-xs font-black text-emerald-700 whitespace-nowrap">
                                {{ number_format((float) $type->prix, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                            {{ $type->description ?: 'Aucune consigne spécifique de prélèvement.' }}
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-mono text-[11px]">
                            {{ $type->unite ? 'Unité : ' . $type->unite : 'Sans unité' }}
                        </span>
                        <form method="POST" action="{{ route('medecin.types.destroy', $type) }}" 
                              onsubmit="return confirm('Supprimer cette analyse du catalogue ?')">
                            @csrf 
                            @method('DELETE')
                            <button class="inline-flex items-center gap-1 text-slate-400 hover:text-rose-600 transition font-semibold" type="submit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                <span>Retirer</span>
                            </button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="col-span-2 rounded-2xl bg-white p-8 text-center text-slate-400 text-xs shadow-sm ring-1 ring-slate-200/80">
                    Aucune analyse présente dans le catalogue pour le moment.
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $types->links() }}
        </div>
    </section>
</div>
@endsection

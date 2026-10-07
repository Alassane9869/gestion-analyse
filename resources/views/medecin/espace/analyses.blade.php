@extends('layouts.medecin')
@section('title', 'Gestion des analyses cliniques')
@section('page-heading', 'Analyses & Examens de Laboratoire')
@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Demandes d’analyses en laboratoire</h2>
            <p class="mt-1 text-xs text-slate-500 font-medium">
                Saisie des résultats biologiques, contrôle de conformité et génération des bulletins médicaux certifiés.
            </p>
        </div>
        <a href="{{ route('medecin.resultats') }}" 
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Historique des bulletins</span>
        </a>
    </div>

    <!-- Filtres d'état professionnels avec pastilles d'indication -->
    @php($currentStatut = request('statut', 'a_traiter'))
    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('medecin.espace.analyses', ['statut' => 'a_traiter', 'search' => $search]) }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $currentStatut === 'a_traiter' ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $currentStatut === 'a_traiter' ? 'bg-white' : 'bg-blue-500' }}"></span>
            <span>À traiter (En attente & En cours)</span>
        </a>
        <a href="{{ route('medecin.espace.analyses', ['statut' => 'en_attente', 'search' => $search]) }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $currentStatut === 'en_attente' ? 'bg-amber-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $currentStatut === 'en_attente' ? 'bg-white' : 'bg-amber-500' }}"></span>
            <span>En attente</span>
        </a>
        <a href="{{ route('medecin.espace.analyses', ['statut' => 'en_cours', 'search' => $search]) }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $currentStatut === 'en_cours' ? 'bg-sky-500 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $currentStatut === 'en_cours' ? 'bg-white' : 'bg-sky-500' }}"></span>
            <span>En cours d’analyse</span>
        </a>
        <a href="{{ route('medecin.espace.analyses', ['statut' => 'completee', 'search' => $search]) }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $currentStatut === 'completee' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $currentStatut === 'completee' ? 'bg-white' : 'bg-emerald-500' }}"></span>
            <span>Validées / Complétées</span>
        </a>
        <a href="{{ route('medecin.espace.analyses', ['statut' => 'tous', 'search' => $search]) }}" 
           class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $currentStatut === 'tous' ? 'bg-slate-800 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
            <span>Toutes les demandes</span>
        </a>
    </div>

    <!-- Barre de recherche -->
    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200/80">
        <form method="GET" action="{{ route('medecin.espace.analyses') }}" class="flex flex-wrap gap-2.5">
            <input type="hidden" name="statut" value="{{ $currentStatut }}">
            <div class="relative flex-1 min-w-[280px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input class="w-full rounded-xl border-slate-300 pl-10 pr-4 py-2 text-xs focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50" 
                       type="search" name="search" value="{{ $search }}" 
                       placeholder="Rechercher par nom d'analyse, patient ou e-mail...">
            </div>
            <button class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 hover:bg-slate-900 px-4 py-2 font-bold text-white text-xs shadow-sm transition">
                <span>Filtrer</span>
            </button>
            @if($search !== '')
                <a class="inline-flex items-center gap-1 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 font-semibold text-slate-700 text-xs transition" 
                   href="{{ route('medecin.espace.analyses', ['statut' => $currentStatut]) }}">
                    <span>Réinitialiser</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Liste des demandes d'analyses -->
    <div class="space-y-4">
    @forelse($analyses as $analyse)
        <article class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">{{ $analyse->analyse_nom }}</h3>
                        <p class="mt-1 text-xs text-slate-600 font-medium">
                            Patient : <strong class="text-slate-800">{{ $analyse->patient_prenom }} {{ $analyse->patient_nom }}</strong> · 
                            <span class="text-slate-400">{{ $analyse->patient_email }}</span>
                        </p>
                        <p class="mt-0.5 text-[11px] text-slate-400 font-mono">
                            Demande émise le {{ \Illuminate\Support\Carbon::parse($analyse->date_demande)->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>

                <div class="text-right">
                    <span class="text-base font-extrabold text-emerald-700 block tracking-tight">
                        {{ number_format((float) $analyse->prix_unitaire, 0, ',', ' ') }} FCFA
                    </span>
                    <div class="mt-1">
                        @if($analyse->statut === 'completee')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                Validée / Conforme
                            </span>
                        @elseif($analyse->statut === 'en_cours')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-1.5"></span>
                                En cours d'analyse
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                En attente de traitement
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Formulaire clinique de saisie et validation -->
            <form method="POST" action="{{ route('medecin.espace.analyses.update', $analyse->ligne_id) }}" 
                  class="mt-4 border-t border-slate-100 pt-4">
                @csrf 
                @method('PATCH')
                
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Statut d'analyse</label>
                        <select class="w-full rounded-xl border-slate-300 text-xs py-2 focus:border-blue-500 focus:ring-blue-500" name="statut" required>
                            <option value="en_attente" @selected($analyse->statut === 'en_attente')>En attente</option>
                            <option value="en_cours" @selected($analyse->statut === 'en_cours')>En cours</option>
                            <option value="completee" @selected($analyse->statut === 'completee')>Validée / Complétée</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Valeur mesurée</label>
                        <input class="w-full rounded-xl border-slate-300 text-xs py-2 focus:border-blue-500 focus:ring-blue-500 font-bold" 
                               type="number" step="any" name="valeur" value="{{ old('valeur', $analyse->valeur) }}" placeholder="Ex: 5.4">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Unité</label>
                        <input class="w-full rounded-xl border-slate-300 text-xs py-2 focus:border-blue-500 focus:ring-blue-500" 
                               name="unite" maxlength="50" value="{{ old('unite', $analyse->unite_resultat ?: $analyse->unite_analyse) }}" placeholder="g/L, mmol/L, %">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Conclusion clinique</label>
                        <input class="w-full rounded-xl border-slate-300 text-xs py-2 focus:border-blue-500 focus:ring-blue-500" 
                               name="remarques" value="{{ old('remarques', $analyse->remarques) }}" placeholder="Remarque ou avis médical">
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 pt-2">
                    <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2 font-bold text-white text-xs shadow-sm transition" 
                            type="submit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Enregistrer & Valider</span>
                    </button>

                    @if($analyse->resultat_id)
                        <a href="{{ route('resultats.bulletin', $analyse->resultat_id) }}" target="_blank" 
                           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition border border-blue-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Consulter le bulletin officiel</span>
                        </a>
                    @endif
                </div>
            </form>
        </article>
    @empty
        <div class="rounded-2xl bg-white p-8 text-center text-slate-500 shadow-sm ring-1 ring-slate-200/80">
            <p class="font-bold text-slate-700 text-sm">Aucune analyse trouvée dans cette catégorie.</p>
            <p class="text-xs text-slate-400 mt-1">Modifiez vos critères de recherche ou sélectionnez un autre statut.</p>
        </div>
    @endforelse
    </div>

    <div class="mt-6">
        {{ $analyses->links() }}
    </div>
</div>
@endsection
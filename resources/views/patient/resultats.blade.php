@extends('layouts.portal')
@section('title', 'Mes Résultats d’Analyses')
@section('page-heading', 'Mes Résultats de Laboratoire')
@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Comptes-rendus d’analyses</h2>
            <p class="mt-1 text-xs text-slate-500 font-medium">
                Consultez vos dosages biologiques validés et téléchargez vos bulletins officiels signés.
            </p>
        </div>
        <a href="{{ route('patient.analyses') }}" 
           class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-bold text-white text-xs shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Commander une analyse</span>
        </a>
    </div>

    <!-- Liste des résultats médicaux -->
    <div class="space-y-4">
    @forelse($resultats as $res)
        <article class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ in_array($res->statut, ['completee', 'normal', 'conforme']) ? 'bg-teal-50 text-teal-700' : 'bg-amber-50 text-amber-700' }}">
                        @if(in_array($res->statut, ['completee', 'normal', 'conforme']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @endif
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">
                            {{ $res->typeAnalyse?->nom ?? $res->analyse?->nom ?? 'Examen biologique' }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Validé le {{ $res->date_resultat ? $res->date_resultat->format('d/m/Y') : $res->created_at->format('d/m/Y') }}
                            @if($res->medecin)
                                · Dr {{ $res->medecin->prenom }} {{ $res->medecin->nom }}
                            @endif
                        </p>
                        @if($res->remarques)
                            <div class="mt-2.5 p-3 rounded-xl bg-slate-50/80 border border-slate-100 text-xs text-slate-700 max-w-xl">
                                <span class="font-bold text-slate-500 block mb-0.5 uppercase tracking-wider text-[10px]">Avis médical</span>
                                {{ $res->remarques }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-right flex flex-col items-end gap-3">
                    @if($res->valeur !== null)
                        <div class="text-xl font-black text-slate-900 tracking-tight">
                            {{ $res->valeur }} <span class="text-xs font-semibold text-slate-500">{{ $res->unite }}</span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        @if(in_array($res->statut, ['completee', 'normal', 'conforme']))
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                Validé
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                En traitement
                            </span>
                        @endif

                        <a href="{{ route('resultats.pdf', ['resultat' => $res->id, 'download' => 1]) }}"
                           class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs hover:bg-emerald-100 transition border border-emerald-200"
                           title="Télécharger le document PDF certifié">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>PDF</span>
                        </a>

                        <a href="{{ route('resultats.bulletin', $res) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition border border-blue-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Bulletin officiel</span>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="rounded-2xl bg-white p-10 text-center text-slate-500 shadow-sm ring-1 ring-slate-200/80">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="font-bold text-slate-800 text-sm">Aucun résultat de laboratoire disponible</h3>
            <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                Lorsque votre laboratoire aura validé vos examens médicaux, vos comptes-rendus officiels apparaîtront ici.
            </p>
        </div>
    @endforelse
    </div>

    <div class="mt-6">
        {{ $resultats->links() }}
    </div>
</div>
@endsection

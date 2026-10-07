@extends('layouts.medecin')
@section('title', 'Historique des Résultats')
@section('page-heading', 'Résultats & Bulletins Médicaux')
@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Archives des résultats cliniques</h2>
            <p class="mt-1 text-xs text-slate-500 font-medium">
                Registre central des examens de biologie médicale validés et accès aux bulletins certifiés.
            </p>
        </div>
        <a class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-5 py-2.5 font-bold text-white text-xs shadow-sm transition" 
           href="{{ route('medecin.espace.analyses') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            <span>Traiter les analyses</span>
        </a>
    </div>

    <!-- Table des résultats archivés -->
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-[11px] font-bold tracking-wider">
                <tr>
                    <th class="p-4">Dossier / Patient</th>
                    <th class="p-4">Groupe</th>
                    <th class="p-4">Examen Biologique</th>
                    <th class="p-4">Date de validation</th>
                    <th class="p-4">Valeur mesurée</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4 text-right">Bulletin Officiel</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($resultats as $resultat)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="p-4">
                            <div class="font-bold text-slate-900 text-sm">
                                {{ $resultat->patient?->prenom }} {{ $resultat->patient?->nom }}
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">#{{ str_pad($resultat->patient_id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="p-4">
                            @if($resultat->patient?->groupe_sanguin)
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    {{ $resultat->patient->groupe_sanguin }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="p-4 font-semibold text-slate-800 text-xs">
                            {{ $resultat->typeAnalyse?->nom ?? $resultat->analyse?->nom }}
                        </td>
                        <td class="p-4 text-slate-500 text-xs">
                            {{ $resultat->date_resultat?->format('d/m/Y') ?: '—' }}
                        </td>
                        <td class="p-4 font-black text-slate-900 text-xs">
                            {{ $resultat->valeur ?? '—' }} <span class="text-[10px] font-normal text-slate-500">{{ $resultat->unite }}</span>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                {{ in_array($resultat->statut, ['completee', 'normal', 'conforme']) ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ in_array($resultat->statut, ['completee', 'normal', 'conforme']) ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                {{ str_replace('_', ' ', $resultat->statut) }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="inline-flex items-center gap-1.5 justify-end">
                                <a href="{{ route('resultats.pdf', ['resultat' => $resultat->id, 'download' => 1]) }}"
                                   class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-xs hover:bg-emerald-100 transition border border-emerald-200"
                                   title="Télécharger le fichier PDF certifié">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>PDF</span>
                                </a>
                                <a href="{{ route('resultats.bulletin', $resultat) }}" target="_blank"
                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition border border-blue-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Consulter</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-8 text-center text-slate-500" colspan="7">
                            <p class="font-bold text-slate-700">Aucun résultat consigné dans l'historique</p>
                            <p class="text-xs text-slate-400 mt-1">Les examens validés depuis le tableau des analyses apparaîtront ici.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $resultats->links() }}
    </div>
</div>
@endsection

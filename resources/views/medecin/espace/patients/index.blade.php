@extends('layouts.medecin')
@section('title', 'Répertoire des Patients')
@section('page-heading', 'Gestion des Patients')
@section('content')
<div class="space-y-6">
    <!-- En-tête avec titre et bouton principal -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Répertoire des dossiers médicaux</h2>
            <p class="mt-1 text-xs text-slate-500 font-medium">
                {{ $patients->total() }} dossier(s) patient(s) répertorié(s) dans la base du laboratoire
            </p>
        </div>
        <a class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 font-bold text-white text-xs shadow-sm transition" 
           href="{{ route('medecin.espace.patients.create') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span>Nouveau patient</span>
        </a>
    </div>

    <!-- Barre de recherche stylisée -->
    <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200/80">
        <form method="GET" action="{{ route('medecin.espace.patients') }}" class="flex flex-wrap gap-2.5">
            <div class="relative flex-1 min-w-[280px]">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input class="w-full rounded-xl border-slate-300 pl-10 pr-4 py-2 text-xs focus:border-blue-500 focus:ring-blue-500 bg-slate-50/50" 
                       type="search" name="search" value="{{ $search }}" 
                       placeholder="Rechercher par nom, prénom, e-mail, téléphone ou groupe sanguin...">
            </div>
            <button class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 hover:bg-slate-900 px-4 py-2 font-bold text-white text-xs shadow-sm transition">
                <span>Filtrer</span>
            </button>
            @if($search !== '')
                <a class="inline-flex items-center gap-1 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-3.5 py-2 font-semibold text-slate-700 text-xs transition" 
                   href="{{ route('medecin.espace.patients') }}">
                    <span>Réinitialiser</span>
                </a>
            @endif
        </form>
    </div>

    <!-- Table des dossiers médicaux -->
    <div class="overflow-x-auto rounded-2xl bg-white shadow-sm ring-1 ring-slate-200/80">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50/75 border-b border-slate-100 text-slate-500 uppercase text-[11px] font-bold tracking-wider">
                <tr>
                    <th class="p-4">Dossier / Patient</th>
                    <th class="p-4">Coordonnées</th>
                    <th class="p-4">Date de naissance</th>
                    <th class="p-4 text-center">Groupe Sanguin</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-400 to-blue-600 flex items-center justify-center text-white font-extrabold text-xs shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                                </div>
                                <div>
                                    <a href="{{ route('medecin.espace.patients.show', $patient) }}" 
                                       class="font-bold text-slate-900 hover:text-blue-600 transition block text-sm">
                                        {{ $patient->prenom }} {{ $patient->nom }}
                                    </a>
                                    <span class="text-[11px] font-mono text-slate-400">#{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <div class="text-xs space-y-0.5">
                                <span class="font-medium text-slate-800 block">{{ $patient->whatsapp_phone ?: ($patient->telephone ?: '—') }}</span>
                                <span class="text-slate-400 block">{{ $patient->email ?: 'Sans adresse e-mail' }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-slate-600 text-xs">
                            {{ $patient->date_naissance ? $patient->date_naissance->format('d/m/Y') : '—' }}
                            @if($patient->sexe)
                                <span class="text-slate-400 ml-1">({{ $patient->sexe }})</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            @if($patient->groupe_sanguin)
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-50 text-rose-700 border border-rose-200">
                                    {{ $patient->groupe_sanguin }}
                                </span>
                            @else
                                <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="p-4 text-right">
                            <div class="inline-flex items-center gap-1.5">
                                <a class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition border border-blue-200" 
                                   href="{{ route('medecin.espace.patients.show', $patient) }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <span>Dossier</span>
                                </a>
                                <a class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-700 font-semibold text-xs hover:bg-slate-200 transition" 
                                   href="{{ route('medecin.espace.patients.edit', $patient) }}" title="Modifier la fiche">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('medecin.espace.patients.destroy', $patient) }}" 
                                      onsubmit="return confirm('Confirmez-vous la suppression de ce dossier patient et de ses accès ?')" class="inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button class="inline-flex items-center justify-center p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" 
                                            type="submit" title="Supprimer définitivement">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-8 text-center text-slate-500" colspan="5">
                            <p class="font-bold text-slate-700">Aucun patient répertorié</p>
                            <p class="text-xs text-slate-400 mt-1">Ajustez vos filtres ou enregistrez une nouvelle fiche clinique.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">
        {{ $patients->links() }}
    </div>
</div>
@endsection
@extends('layouts.medecin')
@section('title', 'Espace Médecin')
@section('page-heading', 'Tableau de bord')
@section('content')
<div class="space-y-6">
    <div class="rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 p-6 sm:p-8 text-white shadow-lg">
        <div class="space-y-2">
            <span class="text-xs uppercase font-bold tracking-wider text-blue-400 block">Centre de pilotage</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Bonjour Dr {{ auth()->user()->name }}</h2>
            <p class="text-sm text-slate-300">Gardez une vue claire sur l’activité médicale et les examens de laboratoire.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <a href="{{ route('medecin.espace.rendez-vous') }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rendez-vous en attente</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <strong class="text-3xl font-extrabold text-slate-900 mt-3 block">{{ $rendezVousEnAttente ?? 0 }}</strong>
            <small class="text-xs text-slate-500 mt-1 block">Demandes à traiter</small>
        </a>

        <a href="{{ route('medecin.resultats') }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Résultats en attente</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <strong class="text-3xl font-extrabold text-slate-900 mt-3 block">{{ $resultatsEnAttente ?? 0 }}</strong>
            <small class="text-xs text-slate-500 mt-1 block">À valider</small>
        </a>

        <a href="{{ route('medecin.types.index') }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Analyses au catalogue</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
            </div>
            <strong class="text-3xl font-extrabold text-slate-900 mt-3 block">{{ $typesCount ?? 0 }}</strong>
            <small class="text-xs text-slate-500 mt-1 block">Examens actifs</small>
        </a>
    </div>

    <div class="flex flex-wrap gap-3">
        <a class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 font-bold text-white text-xs shadow-sm transition" 
           href="{{ route('medecin.resultats.create') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Saisir un résultat</span>
        </a>
        <a class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-5 py-2.5 font-bold text-slate-700 text-xs shadow-sm transition" 
           href="{{ route('medecin.types.index') }}">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Gérer le catalogue d'analyses</span>
        </a>
    </div>
</div>
@endsection

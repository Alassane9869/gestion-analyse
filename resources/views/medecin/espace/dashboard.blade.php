@extends('layouts.medecin')
@section('title', 'Tableau de bord praticien')
@section('page-heading', 'Tableau de bord médical')
@section('content')
<div class="space-y-6">
    <!-- En-tête exécutif & Barre de commande clinique -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 p-6 sm:p-7 text-white shadow-md">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-500/15 px-3 py-1 text-xs font-semibold text-blue-300 ring-1 ring-blue-400/25">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Centre de Diagnostic Biologique · Session Praticien</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Bonjour, Dr {{ trim((auth()->user()->medecin?->prenom ?? '').' '.(auth()->user()->medecin?->nom ?? '')) ?: auth()->user()->name }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                    Pilotage de l’activité médicale, gestion des consultations et validation des analyses de laboratoire.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 sm:self-auto">
                <a href="{{ route('medecin.espace.patients.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Nouveau patient</span>
                </a>
                <a href="{{ route('medecin.espace.analyses') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>Traiter les analyses</span>
                </a>
                <div class="hidden lg:block text-right pl-3 border-l border-white/10">
                    <span class="text-[10px] uppercase tracking-wider text-slate-400 block font-semibold">Date de service</span>
                    <span class="text-xs font-bold text-white capitalize">{{ now()->translatedFormat('l j F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Grille des 4 indicateurs clés (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Patients suivis -->
        <a href="{{ route('medecin.espace.patients') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-blue-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Patients suivis</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $nombrePatients }}</strong>
                <span class="block text-xs text-slate-500 mt-1">Dossiers actifs au centre</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-blue-600 group-hover:text-blue-700">
                <span>Consulter le répertoire</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <!-- 2. Demandes RDV -->
        <a href="{{ route('medecin.espace.rendez-vous') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-amber-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Demandes RDV</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $rendezVousEnAttente }}</strong>
                @if($rendezVousEnAttente > 0)
                    <span class="inline-flex items-center gap-1 text-xs text-amber-600 font-semibold mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> À valider rapidement
                    </span>
                @else
                    <span class="block text-xs text-slate-500 mt-1">Toutes traitées</span>
                @endif
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-amber-600 group-hover:text-amber-700">
                <span>Gérer les demandes</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <!-- 3. Consultations du jour -->
        <a href="{{ route('medecin.espace.rendez-vous') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-emerald-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Séances du jour</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $rendezVousDuJour }}</strong>
                <span class="block text-xs text-slate-500 mt-1">Planifiées aujourd'hui</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-emerald-600 group-hover:text-emerald-700">
                <span>Voir le planning</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <!-- 4. Analyses en cours -->
        <a href="{{ route('medecin.espace.analyses') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-sky-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Analyses en cours</span>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $analysesEnAttenteCount }}</strong>
                <span class="block text-xs text-sky-700 font-medium mt-1">En file au laboratoire</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-sky-600 group-hover:text-sky-700">
                <span>File des examens</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    <!-- Organisation Bi-Colonne Pro (Zone Opérationnelle 65% + Volet Métier 35%) -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        <!-- COLONNE PRINCIPALE (8 / 12) : Consultations & File d'Analyses -->
        <div class="xl:col-span-8 space-y-6">
            <!-- 1. Section Rendez-vous & Consultations -->
            <section class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-200/80">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 leading-tight">Consultations & Rendez-vous</h3>
                            <span class="text-xs text-slate-500">Prochaines séances programmées</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition" 
                           href="{{ route('medecin.espace.rendez-vous') }}">
                            <span>Planning complet</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($prochainsRendezVous as $rdv)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl bg-slate-50/70 hover:bg-slate-50 border border-slate-100 transition gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-blue-600 text-white font-extrabold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                    {{ strtoupper(substr($rdv->patient?->prenom ?? 'P', 0, 1)) }}{{ strtoupper(substr($rdv->patient?->nom ?? 'T', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ $rdv->patient ? route('medecin.espace.patients.show', $rdv->patient) : '#' }}" 
                                           class="font-bold text-slate-900 text-sm hover:text-blue-600 transition">
                                            {{ $rdv->patient?->prenom }} {{ $rdv->patient?->nom }}
                                        </a>
                                        @if($rdv->patient?->groupe_sanguin)
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                                {{ $rdv->patient->groupe_sanguin }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5 flex items-center gap-2">
                                        <span class="font-medium text-slate-700">{{ $rdv->date_heure?->format('d/m/Y à H:i') }}</span>
                                        @if($rdv->motif)
                                            <span>·</span>
                                            <span class="truncate max-w-[200px] text-slate-500">{{ $rdv->motif }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 sm:self-center justify-between sm:justify-end">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $rdv->statut === 'accepte' ? 'bg-emerald-100 text-emerald-800' : ($rdv->statut === 'refuse' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $rdv->statut === 'accepte' ? 'bg-emerald-500' : ($rdv->statut === 'refuse' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                                    {{ ['en_attente' => 'En attente', 'accepte' => 'Confirmé', 'refuse' => 'Refusé'][$rdv->statut] ?? $rdv->statut }}
                                </span>

                                @if($rdv->patient)
                                    <a href="{{ route('medecin.espace.patients.show', $rdv->patient) }}" 
                                       class="px-2.5 py-1 text-xs font-semibold text-slate-600 hover:text-blue-600 bg-white border border-slate-200 rounded-lg hover:border-blue-300 transition">
                                        Dossier
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-10 px-4 text-center rounded-xl bg-slate-50/50 border border-dashed border-slate-200">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">Aucun rendez-vous à venir</h4>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-4">
                                Le planning est dégagé. Les réservations effectuées par vos patients apparaîtront automatiquement dans cette section.
                            </p>
                            <a href="{{ route('medecin.espace.rendez-vous') }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl bg-white border border-slate-300 text-slate-700 hover:text-blue-600 hover:border-blue-300 shadow-sm transition">
                                <span>Consulter l'agenda complet</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- 2. Section Analyses en cours au laboratoire -->
            <section class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-200/80">
                <div class="mb-4 flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 leading-tight">Analyses médicales en cours</h3>
                            <span class="text-xs text-slate-500">Flux d'examens en attente de résultat ou validation</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-sky-700 hover:text-sky-800 bg-sky-50 hover:bg-sky-100 rounded-lg transition" 
                           href="{{ route('medecin.espace.analyses') }}">
                            <span>Toutes les analyses</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($analysesEnAttente as $analyse)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3.5 rounded-xl bg-slate-50/70 hover:bg-slate-50 border border-slate-100 transition gap-3">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-900 text-sm">
                                        {{ $analyse->analyse_nom }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $analyse->statut === 'en_cours' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                        {{ $analyse->statut === 'en_cours' ? 'En cours' : 'En attente' }}
                                    </span>
                                </div>
                                <span class="text-xs text-slate-500 block mt-0.5">
                                    Patient : <strong class="text-slate-700">{{ $analyse->patient_prenom }} {{ $analyse->patient_nom }}</strong> · Demande du {{ \Illuminate\Support\Carbon::parse($analyse->date_demande)->format('d/m/Y') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-3 sm:self-center justify-between sm:justify-end">
                                <span class="font-extrabold text-xs text-emerald-700">
                                    {{ number_format((float) $analyse->prix_unitaire, 0, ',', ' ') }} FCFA
                                </span>

                                <a href="{{ route('medecin.espace.analyses') }}" 
                                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold text-xs transition shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Traiter</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 px-4 text-center rounded-xl bg-slate-50/50 border border-dashed border-slate-200">
                            <div class="w-12 h-12 mx-auto rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-700">File de laboratoire à jour</h4>
                            <p class="text-xs text-slate-400 max-w-sm mx-auto mt-1 mb-4">
                                Aucun prélèvement ou test en attente immédiate de saisie. Les nouvelles analyses ordonnées apparaîtront ici.
                            </p>
                            <a href="{{ route('medecin.espace.analyses') }}" 
                               class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-xl bg-white border border-slate-300 text-slate-700 hover:text-blue-600 hover:border-blue-300 shadow-sm transition">
                                <span>Consulter l'ensemble des analyses</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- VOLET LATÉRAL MÉTIER (4 / 12) : Raccourcis, Patients Récents & Qualité -->
        <div class="xl:col-span-4 space-y-6">
            <!-- 1. Actions Métier Directes -->
            <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
                <div class="mb-4 flex items-center justify-between pb-3 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Actions Rapides</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-2.5">
                    <!-- Nouveau patient -->
                    <a href="{{ route('medecin.espace.patients.create') }}" 
                       class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-emerald-50/70 border border-slate-200/80 hover:border-emerald-200 transition">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <strong class="text-xs font-bold text-slate-900 group-hover:text-emerald-800 block">Nouveau Patient</strong>
                            <span class="text-[11px] text-slate-500 block truncate">Créer un dossier médical</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <!-- Traiter analyses -->
                    <a href="{{ route('medecin.espace.analyses') }}" 
                       class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-blue-50/70 border border-slate-200/80 hover:border-blue-200 transition">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <strong class="text-xs font-bold text-slate-900 group-hover:text-blue-800 block">Traiter les Analyses</strong>
                            <span class="text-[11px] text-slate-500 block truncate">Saisir conclusions & unités</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <!-- Catalogue & Tarifs -->
                    <a href="{{ route('medecin.types.index') }}" 
                       class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-teal-50/70 border border-slate-200/80 hover:border-teal-200 transition">
                        <div class="w-9 h-9 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <strong class="text-xs font-bold text-slate-900 group-hover:text-teal-800 block">Catalogue d'Analyses</strong>
                            <span class="text-[11px] text-slate-500 block truncate">Paramètres & tarifs FCFA</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <!-- Bulletins & Résultats -->
                    <a href="{{ route('medecin.resultats') }}" 
                       class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200/80 hover:border-indigo-200 transition">
                        <div class="w-9 h-9 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <strong class="text-xs font-bold text-slate-900 group-hover:text-indigo-800 block">Bulletins & Résultats</strong>
                            <span class="text-[11px] text-slate-500 block truncate">Archives certifiées & impression</span>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </section>

            <!-- 2. Patients Récents (Accès Rapide) -->
            <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
                <div class="mb-3 flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 leading-tight">Dossiers Récents</h3>
                        <span class="text-[11px] text-slate-500">Derniers patients enregistrés</span>
                    </div>
                    <a href="{{ route('medecin.espace.patients') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                        Tous ({{ $nombrePatients }}) →
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($patientsRecents as $patient)
                        <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 font-extrabold text-xs flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('medecin.espace.patients.show', $patient) }}" 
                                       class="text-xs font-bold text-slate-900 hover:text-blue-600 transition block truncate">
                                        {{ $patient->prenom }} {{ $patient->nom }}
                                    </a>
                                    <div class="flex items-center gap-1.5 text-[10px] text-slate-500">
                                        @if($patient->groupe_sanguin)
                                            <span class="font-bold text-rose-600">{{ $patient->groupe_sanguin }}</span>
                                            <span>·</span>
                                        @endif
                                        <span class="truncate">{{ $patient->whatsapp_phone ?: $patient->email }}</span>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('medecin.espace.patients.show', $patient) }}" 
                               class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 px-2 py-1 rounded bg-blue-50 hover:bg-blue-100 transition flex-shrink-0">
                                Fiche
                            </a>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            Aucun dossier patient enregistré.
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- 3. Cartouche Qualité & Laboratoire BioSanté -->
            <section class="rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 p-5 text-white shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Laboratoire BioSanté</h4>
                        <p class="text-[11px] text-slate-300 mt-1 leading-relaxed">
                            Système de gestion certifié ISO 15189. Signature numérique et traçabilité biologique des résultats garanties.
                        </p>
                        <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between text-[10px] text-slate-400">
                            <span>Astreinte biologique</span>
                            <span class="text-emerald-400 font-bold">24h / 24h</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
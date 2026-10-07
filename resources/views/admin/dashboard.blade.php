@extends('layouts.admin')
@section('title', 'Tableau de bord Administrateur')
@section('page-heading', 'Supervision Globale du Système')
@section('content')
<div class="space-y-6">
    <!-- Bannière d'accueil administrateur -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-950 p-6 sm:p-7 text-white shadow-md">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 rounded-full bg-indigo-500/20 px-3 py-1 text-xs font-semibold text-indigo-300 ring-1 ring-indigo-400/30">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    <span>Console Centrale de Pilotage · Accès Root</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    Bonjour, {{ auth()->user()->name }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                    Supervision complète des utilisateurs (médecins, patients, administrateurs), de la sécurité et du flux d’analyses du laboratoire.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-md transition transform hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>Nouvel Utilisateur</span>
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Gérer les comptes</span>
                </a>
            </div>
        </div>

        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Grille des 4 indicateurs clés (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- 1. Utilisateurs Totaux -->
        <a href="{{ route('admin.users.index') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-indigo-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Utilisateurs</span>
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $usersCount }}</strong>
                <span class="block text-xs text-slate-500 mt-1">
                    {{ $medecinsCount }} médecins · {{ $patientsCount }} patients · {{ $adminsCount }} admins
                </span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-indigo-600 group-hover:text-indigo-700">
                <span>Gérer tous les comptes</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <!-- 2. Médecins Inscrits -->
        <a href="{{ route('admin.users.index', ['role' => 'medecin']) }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-blue-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Praticiens Biologistes</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $medecinsCount }}</strong>
                <span class="block text-xs text-blue-700 font-semibold mt-1">Médecins actifs</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-blue-600 group-hover:text-blue-700">
                <span>Filtrer les médecins</span>
                <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>

        <!-- 3. Analyses & Résultats -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Examens Biologiques</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalAnalyses }}</strong>
                <span class="block text-xs text-slate-500 mt-1">
                    {{ $analysesEnAttente }} en attente ou en cours
                </span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-teal-700">
                <span>Traçabilité ISO 15189</span>
                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
            </div>
        </div>

        <!-- 4. Consultations & RDV -->
        <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rendez-vous Système</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalRendezVous }}</strong>
                <span class="block text-xs text-slate-500 mt-1">
                    {{ $rendezVousAujourdhui }} prévus aujourd'hui
                </span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-[11px] font-semibold text-amber-700">
                <span>Planning consolidé</span>
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            </div>
        </div>
    </div>

    <!-- Organisation Bi-Colonne : Utilisateurs Récents & Activité -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">
        <!-- COLONNE GAUCHE (7 / 12) : Derniers utilisateurs inscrits -->
        <section class="xl:col-span-7 rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-200/80">
            <div class="mb-4 flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Derniers comptes utilisateurs</h3>
                    <span class="text-xs text-slate-500">Comptes créés récemment sur le portail</span>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition">
                    Voir tout ({{ $usersCount }}) →
                </a>
            </div>

            <div class="space-y-3">
                @forelse($derniersUtilisateurs as $user)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 hover:bg-slate-50 border border-slate-100 transition gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center font-extrabold text-xs text-white flex-shrink-0 shadow-sm
                                {{ $user->role === 'admin' ? 'bg-indigo-600' : ($user->role === 'medecin' ? 'bg-blue-600' : 'bg-teal-600') }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('admin.users.edit', $user) }}" class="font-bold text-slate-900 text-sm hover:text-indigo-600 transition block truncate">
                                    {{ $user->name }}
                                </a>
                                <span class="text-xs text-slate-500 block truncate">{{ $user->email }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : ($user->role === 'medecin' ? 'bg-blue-100 text-blue-800' : 'bg-teal-100 text-teal-800') }}">
                                {{ $user->role }}
                            </span>
                            <a href="{{ route('admin.users.edit', $user) }}" 
                               class="text-xs font-semibold text-slate-600 hover:text-indigo-600 px-2 py-1 bg-white border border-slate-200 rounded-lg hover:border-indigo-300 transition">
                                Modifier
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-xs text-slate-400">
                        Aucun utilisateur enregistré.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- COLONNE DROITE (5 / 12) : Raccourcis & Santé Système -->
        <div class="xl:col-span-5 space-y-6">
            <!-- Raccourcis rapides admin -->
            <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Opérations Administrateur</h3>
                <div class="space-y-2.5">
                    <a href="{{ route('admin.users.create') }}" 
                       class="group flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-indigo-50/70 border border-slate-200/80 hover:border-indigo-200 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            </div>
                            <div>
                                <strong class="text-xs font-bold text-slate-900 group-hover:text-indigo-800 block">Créer un compte utilisateur</strong>
                                <span class="text-[11px] text-slate-500">Ajouter médecin, patient ou administrateur</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('medecin.types.index') }}" 
                       class="group flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-teal-50/70 border border-slate-200/80 hover:border-teal-200 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <strong class="text-xs font-bold text-slate-900 group-hover:text-teal-800 block">Catalogue des Analyses</strong>
                                <span class="text-[11px] text-slate-500">Tarifs et paramètres du laboratoire</span>
                            </div>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-600 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </section>

            <!-- Cartouche Santé & Sécurité Système -->
            <section class="rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 p-5 text-white shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 text-emerald-400 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">État du Système BioSanté</h4>
                        <p class="text-[11px] text-slate-300 mt-1 leading-relaxed">
                            Base de données SQLite en mode WAL active. Générateur PDF backend opérationnel.
                        </p>
                        <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between text-[10px]">
                            <span class="text-slate-400">Environnement</span>
                            <span class="text-teal-400 font-bold">100% Opérationnel</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

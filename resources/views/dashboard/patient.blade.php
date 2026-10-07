@extends('layouts.portal')
@section('title', 'Espace Santé Patient')
@section('page-heading', 'Tableau de bord patient')
@section('content')
<div class="space-y-6">
    <!-- En-tête patient soigné -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-6 sm:p-8 text-white shadow-lg">
        <div class="relative z-10 flex flex-wrap items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-300 ring-1 ring-blue-400/30">
                    <span class="inline-block w-2 h-2 rounded-full bg-teal-400"></span>
                    <span>Portail Adhérent Sécurisé</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Bonjour, {{ $patient?->prenom ?: auth()->user()->name }}
                </h2>
                <p class="text-sm text-slate-300 max-w-xl leading-relaxed">
                    Accédez en toute confidentialité à vos ordonnances biologiques, comptes-rendus d’analyses et rendez-vous médicaux.
                </p>
            </div>

            <div class="text-right">
                <span class="text-xs uppercase tracking-wider text-slate-400 block font-semibold">Identifiant Patient</span>
                <span class="text-sm font-mono font-bold text-white">#{{ str_pad($patient?->id ?? auth()->id(), 6, '0', STR_PAD_LEFT) }}</span>
                @if($patient?->groupe_sanguin)
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-500/20 text-rose-300 border border-rose-400/30 mt-2">
                        Groupe {{ $patient->groupe_sanguin }}
                    </span>
                @endif
            </div>
        </div>

        <div class="absolute -right-10 -bottom-10 w-64 h-64 rounded-full bg-teal-500/10 blur-3xl pointer-events-none"></div>
    </div>

    <!-- 3 Cartes Métriques KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <!-- RDV à venir -->
        <a href="{{ route('patient.rendez-vous') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-blue-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Rendez-vous à venir</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $patient?->rendezVous?->where('date_heure', '>=', now())->count() ?? 0 }}
                </strong>
                <span class="block text-xs text-slate-500 mt-1">Consultations planifiées</span>
            </div>
        </a>

        <!-- Analyses choisies -->
        <a href="{{ route('patient.analyses') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-teal-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Analyses sollicitées</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $patient?->commandes?->sum(fn ($c) => $c->types->count()) ?? 0 }}
                </strong>
                <span class="block text-xs text-slate-500 mt-1">Examens en commande</span>
            </div>
        </a>

        <!-- Résultats validés -->
        <a href="{{ route('patient.resultats') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-sky-300 transition flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Résultats disponibles</span>
                <div class="w-10 h-10 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center group-hover:scale-105 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>
            <div class="mt-4">
                <strong class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $patient?->resultats?->where('statut', 'completee')->count() ?? 0 }}
                </strong>
                <span class="block text-xs text-sky-700 font-medium mt-1">Bulletins prêts</span>
            </div>
        </a>
    </div>

    <!-- 3 Cartes de raccourcis rapides -->
    <div class="grid gap-4 md:grid-cols-3">
        <a href="{{ route('patient.profil') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-blue-300 transition flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <strong class="font-bold text-slate-900 text-sm block">Mon dossier médical</strong>
                <small class="text-xs text-slate-500 block truncate">Coordonnées et contact WhatsApp</small>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('patient.analyses') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-teal-300 transition flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <strong class="font-bold text-slate-900 text-sm block">Catalogue des examens</strong>
                <small class="text-xs text-slate-500 block truncate">Consulter les tarifs et demander</small>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-teal-600 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>

        <a href="{{ route('patient.rendez-vous') }}" 
           class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md hover:ring-sky-300 transition flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <strong class="font-bold text-slate-900 text-sm block">Prendre rendez-vous</strong>
                <small class="text-xs text-slate-500 block truncate">Choisir un praticien et une heure</small>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-sky-600 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    <!-- Deux sections principales : Rendez-vous récents et Derniers résultats -->
    <div class="grid gap-6 lg:grid-cols-2">
        <!-- Rendez-vous récents -->
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Mes consultations</h3>
                </div>
                <a class="text-xs font-bold text-blue-600 hover:text-blue-700 transition" href="{{ route('patient.rendez-vous') }}">
                    Historique complet →
                </a>
            </div>

            <div class="space-y-3">
                @forelse($patient?->rendezVous ?? [] as $rdv)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/70 transition">
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">
                                {{ $rdv->date_heure?->format('d/m/Y à H:i') }}
                            </span>
                            <span class="text-xs text-slate-500">
                                Dr {{ $rdv->medecin?->prenom }} {{ $rdv->medecin?->nom }}
                            </span>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $rdv->statut === 'accepte' ? 'bg-emerald-100 text-emerald-800' : ($rdv->statut === 'refuse' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $rdv->statut === 'accepte' ? 'bg-emerald-500' : ($rdv->statut === 'refuse' ? 'bg-rose-500' : 'bg-amber-500') }}"></span>
                            {{ ['accepte' => 'Confirmé', 'refuse' => 'Refusé', 'en_attente' => 'En attente'][$rdv->statut] ?? $rdv->statut }}
                        </span>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">
                        Aucun rendez-vous enregistré pour l'instant.
                    </div>
                @endforelse
            </div>
        </section>

        <!-- Derniers résultats -->
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Derniers comptes-rendus</h3>
                </div>
                <a class="text-xs font-bold text-blue-600 hover:text-blue-700 transition" href="{{ route('patient.resultats') }}">
                    Tous mes résultats →
                </a>
            </div>

            <div class="space-y-3">
                @forelse($patient?->resultats ?? [] as $resultat)
                    <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 border border-slate-100 hover:bg-slate-100/70 transition">
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">
                                {{ $resultat->typeAnalyse?->nom ?? $resultat->analyse?->nom }}
                            </span>
                            <span class="text-xs text-slate-500">
                                Validé le {{ $resultat->date_resultat ? $resultat->date_resultat->format('d/m/Y') : $resultat->created_at->format('d/m/Y') }}
                                @if($resultat->valeur !== null)
                                    · <strong class="text-slate-800">{{ $resultat->valeur }} {{ $resultat->unite }}</strong>
                                @endif
                            </span>
                        </div>

                        <a href="{{ route('resultats.bulletin', $resultat) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition border border-blue-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Bulletin PDF</span>
                        </a>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">
                        Aucun résultat de laboratoire disponible.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

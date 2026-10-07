@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="dashboard-hero mb-6 flex flex-col gap-5 rounded-2xl p-6 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Tableau de bord médical</h1>
        </div>

        <div class="flex flex-wrap gap-2" role="tablist" aria-label="Sections du tableau de bord">
            <button class="btn-primary rounded-xl px-4 py-2" type="button" data-panel="overview" aria-selected="true">Vue d’ensemble</button>
            <button class="btn-secondary rounded-xl px-4 py-2" type="button" data-panel="actions" aria-selected="false">Actions rapides</button>
            <button class="btn-secondary rounded-xl px-4 py-2" type="button" data-panel="schedule" aria-selected="false">Synthèse</button>
        </div>
    </div>

    <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2">
        <div class="card-stat rounded-2xl border-l-4 border-l-emerald-500 p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">Patients</h2>
                <span class="badge">{{ $patientsCount }}</span>
            </div>
            <p class="text-3xl font-bold mt-2">{{ $patientsCount }}</p>
        </div>

        <div class="card-stat rounded-2xl border-l-4 border-l-rose-500 p-5">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold">À vérifier</h2>
                <span class="badge badge-warning">{{ $resultatsEnAttente }}</span>
            </div>
            <p class="mt-2 text-3xl font-bold">{{ $resultatsEnAttente }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-[1.2fr_0.8fr]">
        <section class="panel rounded-2xl p-5 panel-section is-active" data-section="overview">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold">Activité récente</h2>
                </div>
                <a class="btn-secondary shrink-0 rounded-xl px-3 py-2 text-sm" href="{{ route('resultats.index') }}">Tout voir</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Taux de conformité</p>
                    <div class="mt-3 h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500" style="width: {{ $tauxReussite }}%"></div>
                    </div>
                    <p class="mt-2 font-semibold">{{ $tauxReussite }}%</p>
                </div>

                <div class="rounded-2xl bg-slate-50 p-4">
                    <p class="text-sm text-slate-500">Résultats enregistrés</p>
                    <div class="mt-3 h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500" style="width: {{ $resultatsCount > 0 ? min(100, $resultatsCount * 10) : 0 }}%"></div>
                    </div>
                    <p class="mt-2 font-semibold">{{ $resultatsCount }}</p>
                </div>
            </div>

            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                @if ($recentResultats->isEmpty())
                    <p class="p-5 text-sm text-slate-500">Aucun résultat récent à afficher.</p>
                @else
                    <table class="w-full min-w-[36rem] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="p-3">Patient</th>
                                <th class="p-3">Analyse</th>
                                <th class="p-3">Statut</th>
                                <th class="p-3">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentResultats as $resultat)
                                <tr class="hover:bg-emerald-50/40">
                                    <td class="p-3 font-semibold">{{ $resultat->patient?->prenom }} {{ $resultat->patient?->nom }}</td>
                                    <td class="p-3 text-slate-600">{{ $resultat->analyse?->nom ?? 'Analyse supprimée' }}</td>
                                    <td class="p-3"><span class="badge {{ $resultat->statut === 'en_attente' ? 'badge-warning' : '' }}">{{ str_replace('_', ' ', $resultat->statut) }}</span></td>
                                    <td class="p-3 text-slate-500">{{ $resultat->date_resultat?->format('d/m/Y') ?: '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </section>

        <section class="panel rounded-2xl p-5 panel-section" data-section="actions">
            <h2 class="text-lg font-semibold mb-4">Actions rapides</h2>
            <div class="space-y-3">
                <a href="{{ route('laboratoires.create') }}" class="block rounded-xl bg-slate-50 px-4 py-3 text-slate-700 font-medium">Créer un laboratoire</a>
                <a href="{{ route('patients.create') }}" class="block rounded-xl bg-blue-50 px-4 py-3 text-blue-700 font-medium">Ajouter un patient</a>
                <a href="{{ route('medecins.create') }}" class="block rounded-xl bg-violet-50 px-4 py-3 text-violet-700 font-medium">Enregistrer un médecin</a>
                <a href="{{ route('analyses.create') }}" class="block rounded-xl bg-amber-50 px-4 py-3 text-amber-700 font-medium">Ajouter un type d’analyse</a>
                <a href="{{ route('resultats.create') }}" class="block rounded-xl bg-emerald-50 px-4 py-3 text-emerald-700 font-medium">Saisir un résultat</a>
            </div>
        </section>

        <section class="panel rounded-2xl p-5 panel-section" data-section="schedule">
            <h2 class="text-lg font-semibold mb-4">Synthèse médicale</h2>
            <ul class="space-y-3 text-sm">
                <li class="rounded-xl bg-slate-50 p-3">Médecins référencés : <strong>{{ $medecinsCount }}</strong></li>
                <li class="rounded-xl bg-slate-50 p-3">Types d’analyses disponibles : <strong>{{ $analysesCount }}</strong></li>
            </ul>
        </section>
    </div>
@endsection

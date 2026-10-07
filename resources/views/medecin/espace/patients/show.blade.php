@extends('layouts.medecin')
@section('title', 'Dossier Clinique · ' . $patient->prenom . ' ' . $patient->nom)
@section('page-heading', 'Dossier Médical Patient')
@section('content')
<div class="space-y-6">
    <!-- Barre de navigation supérieure -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <a class="inline-flex items-center gap-2 font-bold text-xs text-slate-600 hover:text-slate-900 transition" href="{{ route('medecin.espace.patients') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Retour au répertoire</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('medecin.espace.patients.edit', $patient) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50 transition shadow-sm">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Modifier la fiche</span>
            </a>
        </div>
    </div>

    <!-- Carte d'identité clinique du patient -->
    <div class="rounded-2xl bg-white p-6 sm:p-7 shadow-sm ring-1 ring-slate-200/80">
        <div class="flex flex-wrap items-start justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-400 to-blue-600 flex items-center justify-center text-white text-2xl font-black shadow-md flex-shrink-0">
                    {{ strtoupper(substr($patient->prenom, 0, 1)) }}{{ strtoupper(substr($patient->nom, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $patient->prenom }} {{ $patient->nom }}</h2>
                        @if($patient->groupe_sanguin)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-rose-100 text-rose-800 border border-rose-200">
                                Groupe {{ $patient->groupe_sanguin }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 mt-1 font-mono">
                        Dossier Médical #{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }} · 
                        Inscrit le {{ $patient->created_at ? $patient->created_at->format('d/m/Y') : '—' }}
                    </p>
                </div>
            </div>

            @php($phoneRaw = preg_replace('/\D+/', '', (string)($patient->whatsapp_phone ?: $patient->telephone)))
            @if($phoneRaw)
                <a href="https://wa.me/{{ $phoneRaw }}" target="_blank" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold text-xs hover:bg-emerald-100 transition shadow-sm">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span>Contacter sur WhatsApp</span>
                </a>
            @endif
        </div>

        <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div class="p-3.5 bg-slate-50/70 rounded-xl border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Adresse E-mail</span>
                <span class="font-semibold text-slate-800 break-all text-xs block mt-0.5">{{ $patient->email ?: 'Non renseigné' }}</span>
            </div>
            <div class="p-3.5 bg-slate-50/70 rounded-xl border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Ligne Téléphonique</span>
                <span class="font-semibold text-slate-800 text-xs block mt-0.5">{{ $patient->whatsapp_phone ?: ($patient->telephone ?: 'Non renseigné') }}</span>
            </div>
            <div class="p-3.5 bg-slate-50/70 rounded-xl border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Date de naissance & Sexe</span>
                <span class="font-semibold text-slate-800 text-xs block mt-0.5">
                    {{ $patient->date_naissance ? $patient->date_naissance->format('d/m/Y') : 'Non renseigné' }}
                    {{ $patient->sexe ? '('.$patient->sexe.')' : '' }}
                </span>
            </div>
            <div class="p-3.5 bg-slate-50/70 rounded-xl border border-slate-100">
                <span class="text-[10px] uppercase font-bold text-slate-400 block tracking-wider">Domicile</span>
                <span class="font-semibold text-slate-800 text-xs block mt-0.5">{{ $patient->adresse ?: 'Non renseigné' }}</span>
            </div>
        </div>
    </div>

    <!-- Section : Résultats d'analyses & Bulletins officiels -->
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Analyses Médicales & Bulletins ({{ $patient->resultats->count() }})</h3>
                <p class="text-xs text-slate-500">Examens de laboratoire enregistrés pour ce dossier.</p>
            </div>
            <a href="{{ route('medecin.espace.analyses', ['search' => $patient->nom]) }}" 
               class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                <span>Traiter une analyse</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($patient->resultats->isNotEmpty())
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold tracking-wider">
                        <tr>
                            <th class="p-3.5">Examen / Paramètre</th>
                            <th class="p-3.5">Date de validation</th>
                            <th class="p-3.5">Valeur mesurée</th>
                            <th class="p-3.5">Statut clinique</th>
                            <th class="p-3.5 text-right">Bulletin Officiel</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($patient->resultats as $res)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-3.5 font-bold text-slate-800 text-xs">
                                    {{ $res->typeAnalyse?->nom ?? $res->analyse?->nom ?? 'Examen de laboratoire' }}
                                </td>
                                <td class="p-3.5 text-slate-500 text-xs">
                                    {{ $res->date_resultat ? $res->date_resultat->format('d/m/Y') : '—' }}
                                </td>
                                <td class="p-3.5 font-black text-slate-900 text-xs">
                                    {{ $res->valeur ?? '—' }} <span class="text-[10px] font-normal text-slate-500">{{ $res->unite }}</span>
                                </td>
                                <td class="p-3.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                        {{ in_array($res->statut, ['completee', 'normal', 'conforme']) ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ in_array($res->statut, ['completee', 'normal', 'conforme']) ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        {{ str_replace('_', ' ', $res->statut) }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-right">
                                    <a href="{{ route('resultats.bulletin', $res) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-bold text-xs hover:bg-blue-100 transition border border-blue-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Bulletin PDF</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-8 text-center text-slate-400 text-xs">
                Aucun examen de laboratoire validé pour ce patient.
            </div>
        @endif
    </div>

    <!-- Section : Historique des Rendez-vous -->
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Rendez-vous médicaux ({{ $patient->rendezVous->count() }})</h3>
                <p class="text-xs text-slate-500">Historique des demandes de consultation associées.</p>
            </div>
        </div>

        @if($patient->rendezVous->isNotEmpty())
            <div class="space-y-3">
                @foreach($patient->rendezVous as $rdv)
                    @php($libelleStatut = ['en_attente' => 'En attente', 'accepte' => 'Confirmé', 'refuse' => 'Refusé'][$rdv->statut] ?? $rdv->statut)
                    <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/70 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 text-xs block">
                                    {{ $rdv->date_heure ? $rdv->date_heure->format('d/m/Y à H:i') : 'Date à convenir' }}
                                </span>
                                @if($rdv->motif)
                                    <span class="text-[11px] text-slate-500 block">Motif : {{ $rdv->motif }}</span>
                                @endif
                            </div>
                        </div>

                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                            {{ $rdv->statut === 'accepte' ? 'bg-emerald-100 text-emerald-800' : ($rdv->statut === 'en_attente' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $rdv->statut === 'accepte' ? 'bg-emerald-500' : ($rdv->statut === 'en_attente' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                            {{ $libelleStatut }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center text-slate-400 text-xs">
                Aucun rendez-vous consigné pour ce patient.
            </div>
        @endif
    </div>
</div>
@endsection

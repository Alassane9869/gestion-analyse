@extends('layouts.medecin')
@section('title', 'Gestion des Rendez-vous')
@section('page-heading', 'Agenda & Consultations')
@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Demandes de consultations</h2>
            <p class="mt-1 text-xs text-slate-500 font-medium">
                Validation des créneaux horaires sollicités par les patients pour prélèvements ou avis médical.
            </p>
        </div>
    </div>

    <!-- Liste des rendez-vous -->
    <div class="space-y-4">
    @forelse($rendezVous as $rdv)
        @php($libelleStatut = ['en_attente' => 'En attente', 'accepte' => 'Confirmé', 'refuse' => 'Refusé / Annulé'][$rdv->statut] ?? $rdv->statut)
        @php($phoneRaw = preg_replace('/\D+/', '', (string)($rdv->patient?->whatsapp_phone ?: $rdv->patient?->telephone)))

        <article class="rounded-2xl bg-white p-5 sm:p-6 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">
                            @if($rdv->patient)
                                <a href="{{ route('medecin.espace.patients.show', $rdv->patient) }}" class="hover:text-blue-600 transition">
                                    {{ $rdv->patient->prenom }} {{ $rdv->patient->nom }}
                                </a>
                            @else
                                <span class="text-slate-500">Patient externe</span>
                            @endif
                        </h3>
                        
                        <p class="mt-1 text-xs font-semibold text-slate-700">
                            Créneau : <span class="text-blue-600 font-bold">{{ $rdv->date_heure?->format('d/m/Y à H:i') ?: 'Non défini' }}</span>
                        </p>

                        @if($rdv->patient)
                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    {{ $rdv->patient->whatsapp_phone ?: ($rdv->patient->telephone ?: 'Sans numéro') }}
                                </span>
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    {{ $rdv->patient->email }}
                                </span>
                                @if($phoneRaw)
                                    <a href="https://wa.me/{{ $phoneRaw }}" target="_blank" 
                                       class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 font-bold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        <span>WhatsApp</span>
                                    </a>
                                @endif
                            </div>
                        @endif

                        @if($rdv->motif)
                            <div class="mt-3 p-3 bg-slate-50/70 rounded-xl border border-slate-100 text-xs text-slate-700">
                                <span class="font-bold text-slate-500 block mb-0.5 tracking-wider uppercase text-[10px]">Motif de consultation</span>
                                {{ $rdv->motif }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-right flex flex-col items-end gap-3">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $rdv->statut === 'accepte' ? 'bg-emerald-100 text-emerald-800' : ($rdv->statut === 'en_attente' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $rdv->statut === 'accepte' ? 'bg-emerald-500' : ($rdv->statut === 'en_attente' ? 'bg-amber-500' : 'bg-rose-500') }}"></span>
                        {{ $libelleStatut }}
                    </span>

                    <!-- Actions médicales -->
                    <div class="flex items-center gap-2">
                        @if($rdv->statut === 'en_attente')
                            <form method="POST" action="{{ route('medecin.espace.rendez-vous.update', $rdv) }}">
                                @csrf 
                                @method('PATCH')
                                <input type="hidden" name="statut" value="accepte">
                                <button class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-3.5 py-1.5 font-bold text-white text-xs shadow-sm transition" type="submit">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Confirmer</span>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('medecin.espace.rendez-vous.update', $rdv) }}">
                                @csrf 
                                @method('PATCH')
                                <input type="hidden" name="statut" value="refuse">
                                <button class="inline-flex items-center gap-1 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 px-3 py-1.5 font-semibold text-slate-700 text-xs transition" type="submit">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    <span>Décliner</span>
                                </button>
                            </form>
                        @elseif($rdv->statut === 'accepte')
                            <form method="POST" action="{{ route('medecin.espace.rendez-vous.update', $rdv) }}">
                                @csrf 
                                @method('PATCH')
                                <input type="hidden" name="statut" value="refuse">
                                <button class="text-xs text-rose-600 hover:text-rose-700 font-semibold px-2 py-1 rounded" type="submit">
                                    Annuler la séance
                                </button>
                            </form>
                        @elseif($rdv->statut === 'refuse')
                            <form method="POST" action="{{ route('medecin.espace.rendez-vous.update', $rdv) }}">
                                @csrf 
                                @method('PATCH')
                                <input type="hidden" name="statut" value="accepte">
                                <button class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold px-2 py-1 rounded" type="submit">
                                    Réactiver (Confirmer)
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('medecin.espace.rendez-vous.destroy', $rdv) }}" 
                              onsubmit="return confirm('Confirmez-vous la suppression de ce rendez-vous ?')" class="inline">
                            @csrf 
                            @method('DELETE')
                            <button class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition" title="Supprimer" type="submit">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="rounded-2xl bg-white p-8 text-center text-slate-500 shadow-sm ring-1 ring-slate-200/80">
            <p class="font-bold text-slate-700 text-sm">Aucun rendez-vous consigné</p>
            <p class="text-xs text-slate-400 mt-1">Les consultations demandées par les patients apparaîtront ici.</p>
        </div>
    @endforelse
    </div>

    <div class="mt-6">
        {{ $rendezVous->links() }}
    </div>
</div>
@endsection
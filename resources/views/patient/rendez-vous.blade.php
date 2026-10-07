@extends('layouts.portal')
@section('title', 'Mes Rendez-vous')
@section('page-heading', 'Consultations & Prélèvements')
@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Prise de rendez-vous médical</h2>
        <p class="mt-1 text-xs text-slate-500 font-medium">
            Planifiez une consultation avec un médecin biologiste ou réservez votre créneau de prélèvement.
        </p>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1fr_1.2fr]">
        <!-- Formulaire de demande -->
        <section>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Demande de créneau</h3>
                        <p class="text-xs text-slate-500">Sélectionnez le médecin et la date souhaitée.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('patient.rendez-vous.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="medecin_id">Médecin / Biologiste *</label>
                        <select id="medecin_id" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" name="medecin_id" required>
                            <option value="">Sélectionnez un médecin</option>
                            @foreach($medecins as $medecin)
                                <option value="{{ $medecin->id }}" @selected(old('medecin_id') == $medecin->id)>
                                    Dr {{ $medecin->prenom }} {{ $medecin->nom }}{{ $medecin->specialite ? ' · '.$medecin->specialite : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="date_heure">Date et heure souhaitées *</label>
                        <input id="date_heure" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500 font-semibold" 
                               type="datetime-local" name="date_heure" 
                               min="{{ now()->format('Y-m-d\TH:i') }}" value="{{ old('date_heure') }}" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="motif">Motif de consultation / Examens envisagés</label>
                        <textarea id="motif" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                                  name="motif" rows="3" 
                                  placeholder="Ex: Prélèvement sanguin à jeun, interprétation des analyses, contrôle régulier...">{{ old('motif') }}</textarea>
                    </div>

                    <button class="inline-flex items-center justify-center gap-2 w-full rounded-xl bg-blue-600 hover:bg-blue-700 py-3 font-bold text-white text-xs shadow-sm transition" type="submit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span>Transmettre ma demande de rendez-vous</span>
                    </button>
                </form>
            </div>
        </section>

        <!-- Historique des consultations -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Historique des consultations</h3>
                    <p class="text-xs text-slate-500">Statut de vos demandes envoyées.</p>
                </div>
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    {{ $rendezVous->count() }} consultation(s)
                </span>
            </div>

            <div class="space-y-3">
                @forelse($rendezVous as $rdv)
                    <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200/80 hover:shadow-md transition">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <strong class="text-sm font-bold text-slate-900 block">
                                    Dr {{ $rdv->medecin?->prenom }} {{ $rdv->medecin?->nom }}
                                </strong>
                                <span class="text-xs text-blue-600 font-semibold block mt-0.5">
                                    {{ $rdv->medecin?->specialite ?: 'Biologie Médicale' }}
                                </span>
                                <div class="mt-2 text-xs text-slate-500 flex items-center gap-1.5 font-medium">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $rdv->date_heure?->format('d/m/Y à H:i') }}</span>
                                </div>
                                @if($rdv->motif)
                                    <div class="mt-2.5 p-2.5 rounded-xl bg-slate-50/70 border border-slate-100 text-xs text-slate-700 max-w-md">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Motif</span>
                                        {{ $rdv->motif }}
                                    </div>
                                @endif
                            </div>

                            <div>
                                @if($rdv->statut === 'accepte')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Confirmé
                                    </span>
                                @elseif($rdv->statut === 'refuse')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                        Non retenu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        En attente
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-2xl bg-white p-8 text-center text-slate-400 text-xs shadow-sm ring-1 ring-slate-200/80">
                        Aucune demande de rendez-vous enregistrée.
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

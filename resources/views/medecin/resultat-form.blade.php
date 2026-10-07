@extends('layouts.medecin')
@section('title', 'Saisie d’un résultat médical')
@section('page-heading', 'Saisie d’un Résultat Clinique')
@section('content')
<div class="max-w-3xl space-y-5">
    <div class="flex items-center justify-between">
        <a class="inline-flex items-center gap-2 font-bold text-xs text-slate-600 hover:text-slate-900 transition" href="{{ route('medecin.resultats') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Retour aux résultats</span>
        </a>
    </div>

    <form method="POST" action="{{ route('medecin.resultats.store') }}" 
          class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-200/80 space-y-6">
        @csrf

        <div class="border-b border-slate-100 pb-4">
            <h3 class="text-base font-bold text-slate-900">Enregistrement d'un dosage biologique</h3>
            <p class="text-xs text-slate-500 mt-0.5">Le résultat sera automatiquement disponible sur le portail du patient et certifié.</p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="patient_id">Dossier Patient *</label>
                <select id="patient_id" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" name="patient_id" required>
                    <option value="">Sélectionnez un patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->prenom }} {{ $patient->nom }} ({{ $patient->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="type_analyse_id">Examen / Type d'analyse *</label>
                <select id="type_analyse_id" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" name="type_analyse_id" required>
                    <option value="">Sélectionnez une analyse</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="date_resultat">Date d'examen / Validation *</label>
                <input id="date_resultat" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500 font-semibold" 
                       type="date" name="date_resultat" value="{{ now()->format('Y-m-d') }}" required>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="statut">Statut biologique</label>
                <select id="statut" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" name="statut">
                    <option value="completee">Validée / Conforme</option>
                    <option value="en_cours">En cours de traitement</option>
                    <option value="en_attente">En attente de prélèvement</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="valeur">Valeur mesurée</label>
                <input id="valeur" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500 font-bold" 
                       type="number" step="any" name="valeur" placeholder="Ex: 5.4">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="unite">Unité internationale</label>
                <input id="unite" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                       name="unite" placeholder="g/L, mmol/L, %">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="remarques">Interprétation clinique & Conclusion</label>
                <textarea id="remarques" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                          name="remarques" rows="3" placeholder="Commentaire du biologiste ou valeurs de référence..."></textarea>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('medecin.resultats') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-bold text-xs hover:bg-slate-50 transition">
                Annuler
            </a>
            <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-6 py-2.5 font-bold text-white text-xs shadow-sm transition" type="submit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Enregistrer le résultat</span>
            </button>
        </div>
    </form>
</div>
@endsection

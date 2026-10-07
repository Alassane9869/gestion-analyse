@extends('layouts.portal')
@section('title', 'Catalogue des Analyses')
@section('page-heading', 'Commander des examens')
@section('content')
<div class="space-y-6">
    <!-- En-tête -->
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Catalogue des examens biologiques</h2>
        <p class="mt-1 text-xs text-slate-500 font-medium">
            Sélectionnez les analyses prescrites ou souhaitées. Le montant total en FCFA est calculé instantanément.
        </p>
    </div>

    <form method="POST" action="{{ route('patient.analyses.commander') }}" x-data="{
        selected: [],
        prices: {
            @foreach($types as $type)
                '{{ $type->id }}': {{ (float) $type->prix }},
            @endforeach
        },
        get total() {
            return this.selected.reduce((sum, id) => sum + (this.prices[id] || 0), 0);
        },
        formatMoney(val) {
            return new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
        }
    }">
        @csrf

        <!-- Barre de récapitulatif interactive -->
        <div class="sticky top-4 z-20 mb-6 flex flex-wrap items-center justify-between gap-4 bg-white/95 backdrop-blur-md p-4 sm:p-5 rounded-2xl border border-slate-200/90 shadow-md">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-extrabold text-sm">
                    <span x-text="selected.length">0</span>
                </div>
                <div>
                    <span class="text-xs font-bold text-slate-700 block">Examens sélectionnés</span>
                    <span class="text-[11px] text-slate-400">Ajoutés à votre panier médical</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block">Total estimé</span>
                    <strong class="text-xl font-extrabold text-slate-900 tracking-tight" x-text="formatMoney(total)">0 FCFA</strong>
                </div>
                <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 font-bold text-white text-xs shadow-sm transition" 
                        :disabled="selected.length === 0" :class="{ 'opacity-50 cursor-not-allowed': selected.length === 0 }">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Valider ma demande</span>
                </button>
            </div>
        </div>

        <!-- Grille des analyses -->
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($types as $type)
                <label class="rounded-2xl bg-white p-5 cursor-pointer block border border-slate-200/80 shadow-sm transition hover:border-teal-400 hover:shadow-md"
                       :class="{ 'border-teal-500 bg-teal-50/30 shadow-md ring-2 ring-teal-200': selected.includes('{{ $type->id }}') }">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <input class="w-4 h-4 rounded border-slate-300 text-teal-600 focus:ring-teal-500 cursor-pointer" 
                                   type="checkbox" name="types[]" value="{{ $type->id }}" x-model="selected">
                            <strong class="text-sm font-bold text-slate-900">{{ $type->nom }}</strong>
                        </div>
                        @if($type->code)
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-slate-100 rounded text-slate-500">
                                {{ $type->code }}
                            </span>
                        @endif
                    </div>

                    <p class="mt-2 text-xs text-slate-500 leading-relaxed min-h-[34px]">
                        {{ $type->description ?: 'Bilan biologique standardisé en laboratoire.' }}
                    </p>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">Tarif officiel</span>
                        <strong class="text-sm font-extrabold text-teal-700">
                            {{ number_format((float) $type->prix, 0, ',', ' ') }} FCFA
                        </strong>
                    </div>
                </label>
            @empty
                <div class="col-span-full rounded-2xl bg-white p-10 text-center text-slate-400 text-xs shadow-sm ring-1 ring-slate-200/80">
                    Aucun examen d'analyse n'est disponible à la commande pour le moment.
                </div>
            @endforelse
        </div>
    </form>
</div>
@endsection

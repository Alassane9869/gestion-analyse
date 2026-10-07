@extends('layout.app')
@section('title', 'Détail du patient')
@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">{{ $patient->prenom }} {{ $patient->nom }}</h1>
            <a class="btn-secondary rounded-xl px-4 py-2" href="{{ route('patients.edit', $patient) }}">Modifier</a>
        </div>
        <div class="panel rounded-2xl bg-white p-6 space-y-3">
            <p><strong>Email :</strong> {{ $patient->email ?: '—' }}</p>
            <p><strong>Téléphone :</strong> {{ $patient->telephone ?: '—' }}</p>
            <p><strong>Date de naissance :</strong> {{ $patient->date_naissance?->format('d/m/Y') ?: '—' }}</p>
            <p><strong>Sexe :</strong> {{ $patient->sexe ?: '—' }}</p>
            <p><strong>Laboratoire :</strong> {{ $patient->laboratoire?->nom ?: '—' }}</p>
            <p><strong>Adresse :</strong> {{ $patient->adresse ?: '—' }}</p>
        </div>

        <div class="panel mt-6 rounded-2xl bg-white p-6">
            <h2 class="mb-4 text-xl font-semibold">Types d’analyses sélectionnés</h2>
            @if($patient->analyses->isEmpty())
                <p class="text-slate-500">Aucune analyse sélectionnée.</p>
            @else
                <ul class="grid gap-3 md:grid-cols-2">
                    @foreach($patient->analyses as $analyse)
                        <li class="rounded-lg border border-slate-200 p-3">
                            <span class="block font-medium">{{ $analyse->nom }}</span>
                            <span class="text-sm text-slate-500">{{ $analyse->code }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endsection

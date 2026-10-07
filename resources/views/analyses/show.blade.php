@extends('layout.app')
@section('title', 'Détail du type d’analyse')
@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold">{{ $analyse->nom }}</h1>
            <a class="btn-secondary rounded-xl px-4 py-2" href="{{ route('analyses.edit', $analyse) }}">Modifier</a>
        </div>
        <div class="panel rounded-2xl bg-white p-6 space-y-3">
            <p><strong>Code :</strong> {{ $analyse->code }}</p>
            <p><strong>Unité :</strong> {{ $analyse->unite ?: '—' }}</p>
            <p><strong>Prix :</strong> {{ number_format((float) $analyse->prix, 0, '', ' ') }} FCFA</p>
            <p><strong>Durée :</strong> {{ $analyse->duree_minute ? $analyse->duree_minute . ' minutes' : '—' }}</p>
            <p><strong>Laboratoire :</strong> {{ $analyse->laboratoire?->nom ?: '—' }}</p>
            <p><strong>Description :</strong> {{ $analyse->description ?: '—' }}</p>
        </div>
    </div>
@endsection

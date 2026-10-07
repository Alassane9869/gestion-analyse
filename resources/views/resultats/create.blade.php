@extends('layout.app')
@section('title', 'Nouveau résultat')
@section('content')<div class="mx-auto max-w-4xl"><h1 class="mb-6 text-3xl font-bold">Nouveau résultat</h1><form method="POST" action="{{ route('resultats.store') }}" class="panel rounded-2xl bg-white p-6">@csrf @include('resultats.form')<div class="mt-6 flex gap-3"><button class="btn-primary rounded-xl px-4 py-2">Enregistrer</button><a class="btn-secondary rounded-xl px-4 py-2" href="{{ route('resultats.index') }}">Annuler</a></div></form></div>@endsection

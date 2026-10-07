@extends('layout.app')
@section('title', 'Modifier le type d’analyse')
@section('content')<div class="mx-auto max-w-3xl"><h1 class="mb-6 text-3xl font-bold">Modifier l’analyse</h1><form method="POST" action="{{ route('analyses.update', $analyse) }}" class="panel rounded-2xl bg-white p-6">@csrf @method('PUT') @include('analyses.form')<div class="mt-6 flex gap-3"><button class="btn-primary rounded-xl px-4 py-2">Enregistrer</button><a class="btn-secondary rounded-xl px-4 py-2" href="{{ route('analyses.index') }}">Annuler</a></div></form></div>@endsection

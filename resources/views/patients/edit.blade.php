@extends('layout.app')
@section('title', 'Modifier le patient')
@section('content')<div class="mx-auto max-w-3xl"><h1 class="mb-6 text-3xl font-bold">Modifier le patient</h1><form method="POST" action="{{ route('patients.update', $patient) }}" class="panel rounded-2xl bg-white p-6">@csrf @method('PUT') @include('patients.form')<div class="mt-6 flex gap-3"><button class="btn-primary rounded-xl px-4 py-2">Enregistrer</button><a class="btn-secondary rounded-xl px-4 py-2" href="{{ route('patients.index') }}">Annuler</a></div></form></div>@endsection

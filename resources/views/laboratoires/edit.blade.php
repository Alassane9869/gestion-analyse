@extends('layout.app')
@section('title', 'Modifier le laboratoire')
@section('content')<div class="mx-auto max-w-3xl"><h1 class="mb-6 text-3xl font-bold">Modifier le laboratoire</h1><form method="POST" action="{{ route('laboratoires.update', $laboratoire) }}" class="panel rounded-2xl bg-white p-6">@csrf @method('PUT') @include('laboratoires.form')<div class="mt-6 flex gap-3"><button class="btn-primary rounded-xl px-4 py-2" type="submit">Enregistrer</button><a class="btn-secondary rounded-xl px-4 py-2" href="{{ route('laboratoires.index') }}">Annuler</a></div></form></div>@endsection

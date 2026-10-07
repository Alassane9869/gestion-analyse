@extends('layout.app')
@section('title', 'Types d’analyses')
@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <p class="text-sm uppercase tracking-[0.2em] text-blue-600 font-semibold">Catalogue</p>
            <h1 class="text-3xl font-bold">Types d’analyses</h1>
        </div>
        <a href="{{ route('analyses.create') }}" class="btn-primary rounded-xl px-4 py-2">Nouveau type d’analyse</a>
    </div>

    @if($analyses->isEmpty())
        <div class="panel rounded-2xl bg-white p-8 text-center text-slate-500">Aucun type d’analyse enregistré.</div>
    @else
        <div class="table-card overflow-x-auto rounded-2xl bg-white">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-sm text-slate-500">
                    <tr>
                        <th class="p-4">Code</th>
                        <th class="p-4">Type d’analyse</th>
                        <th class="p-4">Prix</th>
                        <th class="p-4">Laboratoire</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($analyses as $analyse)
                        <tr>
                            <td class="p-4 font-mono font-semibold">{{ $analyse->code }}</td>
                            <td class="p-4">{{ $analyse->nom }}<br><span class="text-sm text-slate-500">{{ $analyse->unite ?: 'Unité non définie' }}</span></td>
                            <td class="p-4">{{ number_format((float) $analyse->prix, 0, '', ' ') }} FCFA</td>
                            <td class="p-4">{{ $analyse->laboratoire?->nom ?: '—' }}</td>
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a class="btn-secondary rounded-lg px-3 py-2 text-sm" href="{{ route('analyses.show', $analyse) }}">Voir</a>
                                    <a class="btn-secondary rounded-lg px-3 py-2 text-sm" href="{{ route('analyses.edit', $analyse) }}">Modifier</a>
                                    <form method="POST" action="{{ route('analyses.destroy', $analyse) }}" onsubmit="return confirm('Supprimer ce type d’analyse ?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg bg-red-50 px-3 py-2 text-sm text-red-700">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

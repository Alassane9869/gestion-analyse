@if($errors->any())
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block font-medium">Code *</label>
        <input class="form-control w-full" name="code" value="{{ old('code', $analyse->code ?? '') }}" required>
    </div>
    <div>
        <label class="mb-1 block font-medium">Nom du type d’analyse *</label>
        <input class="form-control w-full" name="nom" value="{{ old('nom', $analyse->nom ?? '') }}" required>
    </div>
    <div>
        <label class="mb-1 block font-medium">Unité</label>
        <input class="form-control w-full" name="unite" value="{{ old('unite', $analyse->unite ?? '') }}" placeholder="mg/L, g/dL...">
    </div>
    <div>
        <label class="mb-1 block font-medium">Prix en FCFA</label>
        <input class="form-control w-full" type="number" min="0" step="1" name="prix" value="{{ old('prix', (int) ($analyse->prix ?? 0)) }}">
    </div>
    <div>
        <label class="mb-1 block font-medium">Durée (minutes)</label>
        <input class="form-control w-full" type="number" min="1" name="duree_minute" value="{{ old('duree_minute', $analyse->duree_minute ?? '') }}">
    </div>
</div>

<div>
    <label class="mb-1 block font-medium">Laboratoire</label>
    <select class="form-control w-full" name="laboratoire_id">
        <option value="">Aucun</option>
        @foreach($laboratoires as $laboratoire)
            <option value="{{ $laboratoire->id }}" @selected((string) old('laboratoire_id', $analyse->laboratoire_id ?? '') === (string) $laboratoire->id)>{{ $laboratoire->nom }}</option>
        @endforeach
    </select>
</div>

<div class="mt-4">
    <label class="mb-1 block font-medium">Description</label>
    <textarea class="form-control w-full" name="description" rows="4">{{ old('description', $analyse->description ?? '') }}</textarea>
</div>

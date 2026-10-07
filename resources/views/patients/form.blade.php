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
        <label class="mb-1 block font-medium">Prénom *</label>
        <input class="form-control w-full" name="prenom" value="{{ old('prenom', $patient->prenom ?? '') }}" required>
    </div>
    <div>
        <label class="mb-1 block font-medium">Nom *</label>
        <input class="form-control w-full" name="nom" value="{{ old('nom', $patient->nom ?? '') }}" required>
    </div>
    <div>
        <label class="mb-1 block font-medium">Email</label>
        <input class="form-control w-full" type="email" name="email" value="{{ old('email', $patient->email ?? '') }}">
    </div>
    <div>
        <label class="mb-1 block font-medium">Téléphone</label>
        <input class="form-control w-full" name="telephone" value="{{ old('telephone', $patient->telephone ?? '') }}">
    </div>
    <div>
        <label class="mb-1 block font-medium">Date de naissance</label>
        <input class="form-control w-full" type="date" name="date_naissance" value="{{ old('date_naissance', isset($patient) && $patient->date_naissance ? $patient->date_naissance->format('Y-m-d') : '') }}">
    </div>
    <div>
        <label class="mb-1 block font-medium">Sexe</label>
        <select class="form-control w-full" name="sexe">
            <option value="">Non précisé</option>
            @foreach(['F' => 'Femme', 'M' => 'Homme', 'Autre' => 'Autre'] as $value => $label)
                <option value="{{ $value }}" @selected(old('sexe', $patient->sexe ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-4">
    <label class="mb-1 block font-medium">Laboratoire</label>
    <select class="form-control w-full" name="laboratoire_id">
        <option value="">Aucun</option>
        @foreach($laboratoires as $laboratoire)
            <option value="{{ $laboratoire->id }}" @selected((string) old('laboratoire_id', $patient->laboratoire_id ?? '') === (string) $laboratoire->id)>{{ $laboratoire->nom }}</option>
        @endforeach
    </select>
</div>

<div class="mt-4">
    <label class="mb-1 block font-medium">Adresse</label>
    <textarea class="form-control w-full" name="adresse" rows="3">{{ old('adresse', $patient->adresse ?? '') }}</textarea>
</div>

<fieldset class="mt-6 rounded-xl border border-slate-200 p-4">
    <legend class="px-2 font-semibold">Réception des résultats sur WhatsApp</legend>
    <p class="mb-3 text-sm text-slate-500">Le numéro doit être au format international, par exemple +2230102030405.</p>
    <label class="mb-1 block font-medium">Numéro WhatsApp</label>
    <input class="form-control w-full" name="whatsapp_phone" placeholder="+2230102030405" value="{{ old('whatsapp_phone', $patient->whatsapp_phone ?? '') }}">
    <label class="mt-3 flex items-start gap-2 text-sm">
        <input class="mt-1" type="checkbox" name="whatsapp_opt_in" value="1" @checked(old('whatsapp_opt_in', $patient->whatsapp_opt_in ?? false))>
        <span>Le patient consent à recevoir ses résultats par WhatsApp et peut retirer ce consentement à tout moment.</span>
    </label>
</fieldset>

<fieldset class="mt-6 rounded-xl border border-slate-200 p-4">
    <legend class="px-2 font-semibold">Types d’analyses à réaliser</legend>
    <p class="mb-3 text-sm text-slate-500">Cochez les types d’analyses demandés pour ce patient.</p>
    @php
        $selectedAnalyses = old('analyses', isset($patient) ? $patient->analyses->pluck('id')->all() : []);
    @endphp
    @if($analyses->isEmpty())
        <p class="text-sm text-slate-500">Aucun type d’analyse disponible. Créez d’abord un type d’analyse.</p>
    @else
        <div class="grid gap-3 md:grid-cols-2">
            @foreach($analyses as $analyse)
                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-3 hover:bg-slate-50">
                    <input class="mt-1" type="checkbox" name="analyses[]" value="{{ $analyse->id }}" @checked(in_array($analyse->id, $selectedAnalyses))>
                    <span>
                        <span class="block font-medium">{{ $analyse->nom }}</span>
                        <span class="block text-sm text-slate-500">{{ $analyse->code }}{{ $analyse->prix ? ' · ' . number_format((float) $analyse->prix, 0, '', ' ') . ' FCFA' : '' }}</span>
                    </span>
                </label>
            @endforeach
        </div>
    @endif
</fieldset>

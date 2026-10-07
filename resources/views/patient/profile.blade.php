@extends('layouts.portal')
@section('title', 'Mon profil médical')
@section('page-heading', 'Dossier Personnel')
@section('content')
<div class="max-w-3xl space-y-6">
    <!-- En-tête -->
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Informations de santé personnelles</h2>
        <p class="mt-1 text-xs text-slate-500 font-medium">
            Maintenez vos coordonnées à jour pour la notification automatique de vos résultats d'analyses.
        </p>
    </div>

    <div class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-200/80">
        <form method="POST" action="{{ route('patient.profil.update') }}">
            @csrf
            @method('PUT')

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="prenom">Prénom *</label>
                    <input id="prenom" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                           name="prenom" value="{{ old('prenom', $patient->prenom) }}" required>
                    @error('prenom') <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="nom">Nom *</label>
                    <input id="nom" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                           name="nom" value="{{ old('nom', $patient->nom) }}" required>
                    @error('nom') <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="sexe">Sexe</label>
                    <select id="sexe" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" name="sexe">
                        <option value="">Non précisé</option>
                        <option value="Masculin" @selected(old('sexe', $patient->sexe) === 'Masculin' || old('sexe', $patient->sexe) === 'M')>Masculin (M)</option>
                        <option value="Féminin" @selected(old('sexe', $patient->sexe) === 'Féminin' || old('sexe', $patient->sexe) === 'F')>Féminin (F)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="date_naissance">Date de naissance</label>
                    <input id="date_naissance" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                           type="date" name="date_naissance" 
                           value="{{ old('date_naissance', $patient->date_naissance?->format('Y-m-d')) }}" max="{{ now()->format('Y-m-d') }}">
                    @error('date_naissance') <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="groupe_sanguin">Groupe sanguin</label>
                    <select id="groupe_sanguin" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" name="groupe_sanguin">
                        <option value="">Non déterminé</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $groupe)
                            <option value="{{ $groupe }}" @selected(old('groupe_sanguin', $patient->groupe_sanguin) === $groupe)>
                                {{ $groupe }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="whatsapp_phone">Téléphone / WhatsApp</label>
                    <input id="whatsapp_phone" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                           name="whatsapp_phone" placeholder="Format international (ex: +221 77 123 45 67)" 
                           value="{{ old('whatsapp_phone', $patient->whatsapp_phone ?: $patient->telephone) }}">
                    <small class="text-slate-400 block text-[11px] mt-1">Numéro utilisé pour la réception des alertes d’analyses.</small>
                    @error('whatsapp_phone') <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="adresse">Adresse de résidence</label>
                    <input id="adresse" class="w-full rounded-xl border-slate-300 text-xs py-2.5 focus:border-blue-500 focus:ring-blue-500" 
                           name="adresse" placeholder="Quartier, Ville, Pays" 
                           value="{{ old('adresse', $patient->adresse) }}">
                </div>
            </div>

            <div class="mt-8 flex items-center justify-between border-t border-slate-100 pt-5">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>Modifier mot de passe ou email de connexion</span>
                </a>

                <button class="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 px-6 py-2.5 font-bold text-white text-xs shadow-sm transition" type="submit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Enregistrer mon profil</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

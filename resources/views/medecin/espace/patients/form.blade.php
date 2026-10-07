@extends('layouts.medecin')
@section('title', $edition ? 'Modifier un patient' : 'Ajouter un patient')
@section('page-heading', $edition ? 'Modifier le dossier patient' : 'Création d’un dossier patient')
@section('content')
<div class="max-w-4xl space-y-5">
    <div class="flex items-center justify-between">
        <a class="inline-flex items-center gap-2 font-bold text-xs text-slate-600 hover:text-slate-900 transition" href="{{ route('medecin.espace.patients') }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Retour aux patients</span>
        </a>
        @if($edition)
            <a class="inline-flex items-center gap-1.5 font-bold text-blue-600 hover:text-blue-700 text-xs transition" href="{{ route('medecin.espace.patients.show', $patient) }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Consulter le dossier médical</span>
            </a>
        @endif
    </div>

    <form method="POST" action="{{ $edition ? route('medecin.espace.patients.update', $patient) : route('medecin.espace.patients.store') }}" 
          class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-200/80 space-y-7">
        @csrf
        @if($edition) @method('PUT') @endif

        <!-- Section 1 : État Civil -->
        <div>
            <div class="border-b border-slate-100 pb-3 mb-5">
                <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600 block">Section 1</span>
                <h3 class="text-base font-bold text-slate-900 mt-0.5">État Civil & Paramètres Médicaux</h3>
                <p class="text-xs text-slate-500 mt-0.5">Identité du patient reportée sur les ordonnances et comptes-rendus de laboratoire.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="prenom">Prénom *</label>
                    <input id="prenom" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           name="prenom" value="{{ old('prenom', $patient->prenom) }}" required placeholder="Ex: Aminata">
                    @error('prenom')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="nom">Nom *</label>
                    <input id="nom" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           name="nom" value="{{ old('nom', $patient->nom) }}" required placeholder="Ex: Traoré">
                    @error('nom')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="date_naissance">Date de naissance</label>
                    <input id="date_naissance" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           type="date" name="date_naissance" value="{{ old('date_naissance', $patient->date_naissance?->format('Y-m-d')) }}">
                    @error('date_naissance')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="sexe">Sexe</label>
                    <select id="sexe" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" name="sexe">
                        <option value="">Non précisé</option>
                        <option value="M" @selected(old('sexe', $patient->sexe) === 'M' || old('sexe', $patient->sexe) === 'Masculin')>Masculin (M)</option>
                        <option value="F" @selected(old('sexe', $patient->sexe) === 'F' || old('sexe', $patient->sexe) === 'Féminin')>Féminin (F)</option>
                    </select>
                    @error('sexe')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="groupe_sanguin">Groupe sanguin</label>
                    <select id="groupe_sanguin" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" name="groupe_sanguin">
                        <option value="">Non déterminé</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $groupe)
                            <option value="{{ $groupe }}" @selected(old('groupe_sanguin', $patient->groupe_sanguin) === $groupe)>{{ $groupe }}</option>
                        @endforeach
                    </select>
                    @error('groupe_sanguin')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="adresse">Domicile / Résidence</label>
                    <input id="adresse" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           name="adresse" placeholder="Quartier, Ville, Pays" value="{{ old('adresse', $patient->adresse) }}">
                    @error('adresse')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Section 2 : Coordonnées de Contact -->
        <div>
            <div class="border-b border-slate-100 pb-3 mb-5">
                <span class="text-[11px] font-bold uppercase tracking-wider text-teal-600 block">Section 2</span>
                <h3 class="text-base font-bold text-slate-900 mt-0.5">Coordonnées & Canaux de Notification</h3>
                <p class="text-xs text-slate-500 mt-0.5">Canaux utilisés pour l'envoi automatisé des avis de résultats et rappels.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="email">Adresse e-mail de connexion *</label>
                    <input id="email" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           type="email" name="email" value="{{ old('email', $patient->email) }}" required placeholder="patient@exemple.com">
                    @error('email')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="whatsapp_phone">Téléphone / WhatsApp *</label>
                    <input id="whatsapp_phone" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           name="whatsapp_phone" placeholder="Format international (ex: +223 70 00 00 00)" 
                           value="{{ old('whatsapp_phone', $patient->whatsapp_phone ?: $patient->telephone) }}" required>
                    <small class="mt-1 block text-[11px] text-slate-400">Indicatif pays recommandé (+223, +221, +33, etc.).</small>
                    @error('whatsapp_phone')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Section 3 : Identifiants & Sécurité -->
        <div>
            <div class="border-b border-slate-100 pb-3 mb-5">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500 block">Section 3</span>
                <h3 class="text-base font-bold text-slate-900 mt-0.5">Accès au Portail Patient</h3>
                <p class="text-xs text-slate-500 mt-0.5">Permet au patient de se connecter en ligne pour consulter ses bulletins.</p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="password">
                        {{ $edition ? 'Nouveau mot de passe' : 'Mot de passe initial *' }}
                    </label>
                    <input id="password" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           type="password" name="password" autocomplete="new-password" @required(!$edition) placeholder="Minimum 8 caractères">
                    @if($edition)
                        <small class="mt-1 block text-[11px] text-slate-400">Laissez vide pour conserver le mot de passe actuel.</small>
                    @endif
                    @error('password')<p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1" for="password_confirmation">
                        Confirmation du mot de passe
                    </label>
                    <input id="password_confirmation" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-xs py-2.5" 
                           type="password" name="password_confirmation" autocomplete="new-password" @required(!$edition) placeholder="Répéter le mot de passe">
                </div>
            </div>
        </div>

        <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('medecin.espace.patients') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-bold text-xs hover:bg-slate-50 transition">
                Annuler
            </a>
            <button class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 px-6 py-2.5 font-bold text-white text-xs shadow-sm transition" type="submit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $edition ? 'Enregistrer les modifications' : 'Créer le dossier clinique' }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
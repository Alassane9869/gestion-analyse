@extends('layouts.admin')
@section('title', $isEdit ? 'Modifier ' . $user->name : 'Créer un utilisateur')
@section('page-heading', $isEdit ? 'Modification du Compte Utilisateur' : 'Création d\'un Nouveau Compte')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- En-tête -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:border-slate-300 text-slate-600 flex items-center justify-center transition shadow-sm"
               title="Retour à la liste">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    {{ $isEdit ? 'Modifier le compte : ' . $user->name : 'Nouveau Compte Utilisateur' }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500">
                    {{ $isEdit ? 'Mise à jour des identifiants et des fiches associées.' : 'Remplissez les informations ci-dessous pour créer un profil avec ses droits d’accès.' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Carte du formulaire -->
    <form method="POST" action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}" class="rounded-2xl bg-white p-6 sm:p-8 shadow-sm ring-1 ring-slate-200/80 space-y-8">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- 1. Informations d'identification générales -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">1. Identité & Connexion</h3>
                    <p class="text-xs text-slate-500">Ces accès permettent à l'utilisateur de s'authentifier sur le portail.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Nom complet -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nom complet ou Raison <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           placeholder="ex: Dr. Marie Dupont ou Mamadou Sarr" 
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('name') border-rose-300 bg-rose-50/50 @enderror">
                    @error('name')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Adresse e-mail <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           placeholder="ex: utilisateur@biosante.fr" 
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('email') border-rose-300 bg-rose-50/50 @enderror">
                    @error('email')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mot de passe -->
                <div class="sm:col-span-2">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Mot de passe {{ $isEdit ? '(Optionnel - Laisser vide pour conserver l\'actuel)' : '*' }}
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           {{ $isEdit ? '' : 'required' }} 
                           placeholder="{{ $isEdit ? 'Entrez un nouveau mot de passe si vous souhaitez le changer' : 'Minimum 8 caractères' }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition @error('password') border-rose-300 bg-rose-50/50 @enderror">
                    @error('password')
                        <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- 2. Rôle & Attribution des Droits -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">2. Rôle & Droits d'Accès <span class="text-rose-500">*</span></h3>
                <p class="text-xs text-slate-500">Sélectionnez le niveau d'habilitation et l'interface correspondante.</p>
            </div>

            @php
                $currentRole = old('role', $user->role ?? 'patient');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <!-- Patient -->
                <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none {{ $currentRole === 'patient' ? 'border-teal-500 bg-teal-50/40 ring-2 ring-teal-100' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}" id="role-label-patient">
                    <input type="radio" name="role" value="patient" class="sr-only" {{ $currentRole === 'patient' ? 'checked' : '' }} onchange="toggleRoleSections(this.value)">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-teal-100 text-teal-800">
                            Patient
                        </span>
                        <span class="w-4 h-4 rounded-full border border-teal-500 flex items-center justify-center role-radio-indicator {{ $currentRole === 'patient' ? 'bg-teal-500' : 'bg-white' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        </span>
                    </div>
                    <strong class="text-sm font-bold text-slate-900">Espace Patient</strong>
                    <span class="text-xs text-slate-500 mt-1">Accès aux analyses personnelles, prise de rendez-vous et téléchargement des bulletins PDF officiels.</span>
                </label>

                <!-- Médecin -->
                <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none {{ $currentRole === 'medecin' ? 'border-blue-500 bg-blue-50/40 ring-2 ring-blue-100' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}" id="role-label-medecin">
                    <input type="radio" name="role" value="medecin" class="sr-only" {{ $currentRole === 'medecin' ? 'checked' : '' }} onchange="toggleRoleSections(this.value)">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-800">
                            Médecin
                        </span>
                        <span class="w-4 h-4 rounded-full border border-blue-500 flex items-center justify-center role-radio-indicator {{ $currentRole === 'medecin' ? 'bg-blue-500' : 'bg-white' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        </span>
                    </div>
                    <strong class="text-sm font-bold text-slate-900">Médecin Biologiste</strong>
                    <span class="text-xs text-slate-500 mt-1">Saisie des résultats, validation des examens, gestion du catalogue des analyses et suivi des patients.</span>
                </label>

                <!-- Administrateur -->
                <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none {{ $currentRole === 'admin' ? 'border-indigo-500 bg-indigo-50/40 ring-2 ring-indigo-100' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50' }}" id="role-label-admin">
                    <input type="radio" name="role" value="admin" class="sr-only" {{ $currentRole === 'admin' ? 'checked' : '' }} onchange="toggleRoleSections(this.value)">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800">
                            Administrateur
                        </span>
                        <span class="w-4 h-4 rounded-full border border-indigo-500 flex items-center justify-center role-radio-indicator {{ $currentRole === 'admin' ? 'bg-indigo-500' : 'bg-white' }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        </span>
                    </div>
                    <strong class="text-sm font-bold text-slate-900">Console Système</strong>
                    <span class="text-xs text-slate-500 mt-1">Droits racine, création et gestion des utilisateurs, audit et supervision globale de la plateforme.</span>
                </label>
            </div>
            @error('role')
                <p class="text-xs text-rose-600 font-semibold mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- 3. Coordonnées & Fiches Métier -->
        <div class="space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">3. Informations Complémentaires</h3>
                <p class="text-xs text-slate-500">Données synchronisées automatiquement avec la fiche patient ou médecin.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Numéro de téléphone
                    </label>
                    <input type="text" 
                           id="telephone" 
                           name="telephone" 
                           value="{{ old('telephone', $user->medecin?->telephone ?? $user->patient?->telephone) }}" 
                           placeholder="ex: +221 77 123 45 67 ou 06 12 34 56 78" 
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <!-- Spécialité (Pour Médecin) -->
                <div id="section-medecin-fields" class="{{ $currentRole === 'medecin' ? '' : 'hidden' }}">
                    <label for="specialite" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Spécialité Médicale
                    </label>
                    <input type="text" 
                           id="specialite" 
                           name="specialite" 
                           value="{{ old('specialite', $user->medecin?->specialite ?? 'Biologie médicale') }}" 
                           placeholder="ex: Biologie médicale, Biochimie clinique, Hématologie" 
                           class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                </div>

                <!-- Groupe Sanguin (Pour Patient) -->
                <div id="section-patient-fields" class="{{ $currentRole === 'patient' ? '' : 'hidden' }}">
                    <label for="groupe_sanguin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Groupe Sanguin
                    </label>
                    <select id="groupe_sanguin" 
                            name="groupe_sanguin" 
                            class="w-full px-3.5 py-2.5 rounded-xl text-sm bg-slate-50 border border-slate-200 focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none transition">
                        <option value="">Sélectionner un groupe</option>
                        @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $grp)
                            <option value="{{ $grp }}" {{ old('groupe_sanguin', $user->patient?->groupe_sanguin) === $grp ? 'selected' : '' }}>
                                Groupe {{ $grp }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs sm:text-sm transition">
                Annuler
            </a>
            <button type="submit" 
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs sm:text-sm shadow-sm transition transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>{{ $isEdit ? 'Mettre à jour l\'utilisateur' : 'Créer l\'utilisateur' }}</span>
            </button>
        </div>
    </form>
</div>

<script>
function toggleRoleSections(role) {
    const medSection = document.getElementById('section-medecin-fields');
    const patSection = document.getElementById('section-patient-fields');

    // Mettre à jour l'affichage des sections
    if (role === 'medecin') {
        if (medSection) medSection.classList.remove('hidden');
        if (patSection) patSection.classList.add('hidden');
    } else if (role === 'patient') {
        if (medSection) medSection.classList.add('hidden');
        if (patSection) patSection.classList.remove('hidden');
    } else {
        if (medSection) medSection.classList.add('hidden');
        if (patSection) patSection.classList.add('hidden');
    }

    // Mise à jour visuelle des bordures des cartes
    ['patient', 'medecin', 'admin'].forEach(r => {
        const el = document.getElementById('role-label-' + r);
        if (!el) return;
        const indicator = el.querySelector('.role-radio-indicator');
        if (r === role) {
            el.className = 'relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none ' + 
                (r === 'patient' ? 'border-teal-500 bg-teal-50/40 ring-2 ring-teal-100' : 
                 r === 'medecin' ? 'border-blue-500 bg-blue-50/40 ring-2 ring-blue-100' : 
                 'border-indigo-500 bg-indigo-50/40 ring-2 ring-indigo-100');
            if (indicator) {
                indicator.className = 'w-4 h-4 rounded-full border flex items-center justify-center role-radio-indicator ' +
                    (r === 'patient' ? 'border-teal-500 bg-teal-500' : 
                     r === 'medecin' ? 'border-blue-500 bg-blue-500' : 
                     'border-indigo-500 bg-indigo-500');
            }
        } else {
            el.className = 'relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none border-slate-200 bg-slate-50/50 hover:bg-slate-50';
            if (indicator) {
                indicator.className = 'w-4 h-4 rounded-full border border-slate-300 flex items-center justify-center role-radio-indicator bg-white';
            }
        }
    });
}
</script>
@endsection

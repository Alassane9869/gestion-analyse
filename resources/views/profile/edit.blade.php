@extends(auth()->user()?->isMedecin() ? 'layouts.medecin' : 'layouts.portal')
@section('title', 'Mon Compte & Sécurité')
@section('page-heading', 'Paramètres du compte')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Sécurité & Identifiants de connexion</h2>
        <p class="mt-1 text-xs text-slate-500 font-medium">
            Gérez votre adresse e-mail, modifiez votre mot de passe et protégez vos accès médicaux.
        </p>
    </div>

    <div class="space-y-6">
        <div class="rounded-2xl bg-white p-6 sm:p-7 shadow-sm ring-1 ring-slate-200/80">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Informations personnelles</h3>
                    <p class="text-xs text-slate-500">Mettez à jour votre nom d'utilisateur et votre e-mail de connexion.</p>
                </div>
            </div>
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="rounded-2xl bg-white p-6 sm:p-7 shadow-sm ring-1 ring-slate-200/80">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Sécurité du mot de passe</h3>
                    <p class="text-xs text-slate-500">Choisissez une clé secrète renforcée pour verrouiller l'accès à vos dossiers.</p>
                </div>
            </div>
            @include('profile.partials.update-password-form')
        </div>

        <div class="rounded-2xl bg-white p-6 sm:p-7 shadow-sm ring-1 ring-slate-200/80 border-l-4 border-rose-500">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100">
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-rose-700">Suppression définitive du compte</h3>
                    <p class="text-xs text-slate-500">Action irréversible : toutes les données associées seront effacées.</p>
                </div>
            </div>
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection

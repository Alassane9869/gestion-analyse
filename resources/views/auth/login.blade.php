<x-guest-layout>
    <div class="auth-heading">
        <span class="eyebrow eyebrow--blue">ACCÈS SÉCURISÉ</span>
        <h2>Connexion à votre espace</h2>
        <p>Identifiez-vous pour consulter vos dossiers ou vos prescriptions.</p>
    </div>

    <x-auth-session-status class="auth-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf
        <div>
            <x-input-label for="email" value="Adresse e-mail" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="votre.email@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <span class="form-label">Espace concerné</span>
            <div class="role-tabs" data-role-tabs>
                <label class="role-tab">
                    <input type="radio" name="role" value="patient" @checked(old('role', 'patient') === 'patient')>
                    <span>
                        <svg class="w-4 h-4 text-teal-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Patient</span>
                    </span>
                </label>
                <label class="role-tab">
                    <input type="radio" name="role" value="medecin" @checked(old('role') === 'medecin')>
                    <span>
                        <svg class="w-4 h-4 text-blue-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Praticien</span>
                    </span>
                </label>
                <label class="role-tab">
                    <input type="radio" name="role" value="admin" @checked(old('role') === 'admin')>
                    <span>
                        <svg class="w-4 h-4 text-indigo-600 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Admin</span>
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <div class="form-meta">
            <label for="remember_me" class="check-label">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Mémoriser mes identifiants</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
        </div>

        <div class="auth-submit-row">
            <x-primary-button class="button button--primary button--wide">
                <span>Accéder à mon espace</span>
                <svg class="w-4 h-4 ml-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </x-primary-button>
            @if (Route::has('register'))
                <p>Nouveau patient ? <a href="{{ route('register') }}">Créer mon compte santé</a></p>
            @endif
        </div>
    </form>
</x-guest-layout>

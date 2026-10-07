<x-guest-layout>
    <div class="auth-heading"><span class="eyebrow eyebrow--blue">ESPACE SÉCURISÉ</span><h2>Confirmer votre identité</h2><p>Saisissez votre mot de passe pour continuer.</p></div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Mot de passe" />

            <x-text-input id="password" class="form-input"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="auth-submit-row mt-4"><x-primary-button class="button button--primary button--wide">Confirmer <span>→</span></x-primary-button>
        </div>
    </form>
</x-guest-layout>

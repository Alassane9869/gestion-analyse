<x-guest-layout>
    <div class="auth-heading"><span class="eyebrow eyebrow--blue">RÉCUPÉRATION</span><h2>Mot de passe oublié ?</h2><p>Indiquez votre adresse email et nous vous enverrons un lien de réinitialisation.</p></div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Adresse email" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autofocus placeholder="vous@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="auth-submit-row mt-4"><x-primary-button class="button button--primary button--wide">Envoyer le lien <span>→</span></x-primary-button><p><a href="{{ route('login') }}">Retour à la connexion</a></p>
        </div>
    </form>
</x-guest-layout>

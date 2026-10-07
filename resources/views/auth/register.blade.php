<x-guest-layout>
    <div class="auth-heading"><span class="eyebrow eyebrow--blue">NOUVEAU COMPTE</span><h2>Créer votre espace</h2><p>Inscrivez-vous pour suivre vos analyses médicales.</p></div>
    <form method="POST" action="{{ route('register') }}" class="auth-form">
        @csrf
        <div>
            <x-input-label for="name" value="Nom complet" />
            <x-text-input id="name" class="form-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Votre nom complet" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="email" value="Adresse email" />
            <x-text-input id="email" class="form-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="vous@exemple.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="8 caractères minimum" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmer le mot de passe" />
            <x-text-input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Répétez le mot de passe" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>
        <div class="auth-submit-row"><x-primary-button class="button button--primary button--wide">Créer mon compte <span>→</span></x-primary-button><p>Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </form>
</x-guest-layout>

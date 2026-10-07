<x-guest-layout>
    <div class="auth-heading">
        <span class="eyebrow eyebrow--blue">SÉCURITÉ DU COMPTE</span>
        <h2>Vérification par code OTP</h2>
        <p>Un code confidentiel à 6 chiffres a été envoyé à l'adresse <strong>{{ $email }}</strong>.</p>
    </div>

    @if (session('status'))
        <div class="mb-4 text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg p-3">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify.submit') }}" class="auth-form">
        @csrf
        <div>
            <label for="otp_code" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">Code de vérification (6 chiffres)</label>
            <input id="otp_code" 
                   type="text" 
                   name="otp_code" 
                   required 
                   autofocus 
                   maxlength="6"
                   placeholder="Ex : 482915"
                   autocomplete="one-time-code"
                   style="font-size: 24px; font-family: monospace; letter-spacing: 6px; text-align: center; height: 54px; width: 100%; border: 2px solid #cbd5e1; border-radius: 12px; font-weight: 700; color: #0f172a; outline: none; transition: all .2s;"
                   onfocus="this.style.borderColor='#0284c7'; this.style.boxShadow='0 0 0 3px rgba(2,132,199,0.15)';"
                   onblur="this.style.borderColor='#cbd5e1'; this.style.boxShadow='none';"
            />
            <x-input-error :messages="$errors->get('otp_code')" class="mt-2" />
        </div>

        <div class="mt-6 auth-submit-row">
            <x-primary-button class="button button--primary button--wide" style="height: 48px; font-size: 14px;">
                <span>Confirmer et activer mon compte</span>
                <svg class="w-4 h-4 ml-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </x-primary-button>
        </div>
    </form>

    <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button type="submit" class="font-bold text-sky-600 hover:text-sky-700 transition">
                Renvoyer un nouveau code
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-slate-400 hover:text-slate-600 transition">
                Se déconnecter
            </button>
        </form>
    </div>
</x-guest-layout>

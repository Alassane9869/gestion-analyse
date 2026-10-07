<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\OtpVerificationNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Affiche l'écran de saisie du code OTP.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (!$user || !$user->hasPendingOtp()) {
            return redirect()->route('dashboard');
        }

        return view('auth.verify-otp', [
            'email' => $user->email,
        ]);
    }

    /**
     * Valide le code OTP saisi par l'utilisateur.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Vérifier si le code correspond et n'est pas expiré
        if ($user->otp_code !== trim($request->otp_code)) {
            return back()->withErrors([
                'otp_code' => 'Le code de vérification est incorrect. Veuillez vérifier vos e-mails.',
            ]);
        }

        if ($user->otp_expires_at && $user->otp_expires_at->isPast()) {
            return back()->withErrors([
                'otp_code' => 'Ce code a expiré (validité 15 min). Veuillez cliquer sur "Renvoyer un code".',
            ]);
        }

        // Activation réussie du compte
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('status', 'Votre compte a été vérifié et activé avec succès ! Bienvenue sur BioSanté.');
    }

    /**
     * Renvoie un nouveau code OTP par e-mail.
     */
    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $newOtp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'otp_code' => $newOtp,
            'otp_expires_at' => now()->addMinutes(15),
        ]);

        try {
            $user->notify(new OtpVerificationNotification($newOtp));
        } catch (\Throwable $e) {
            // Continuer sans bloquer si transport de messagerie indisponible
        }

        return back()->with('status', 'Un nouveau code de vérification vous a été envoyé par e-mail.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\OtpVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $hasOtpColumns = \Illuminate\Support\Facades\Schema::hasColumns('users', ['otp_code', 'otp_expires_at']);

        $userAttributes = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
        ];

        if ($hasOtpColumns) {
            $userAttributes['otp_code'] = $otp;
            $userAttributes['otp_expires_at'] = now()->addMinutes(15);
            $userAttributes['email_verified_at'] = null;
        } else {
            $userAttributes['email_verified_at'] = now();
        }

        $user = \Illuminate\Support\Facades\DB::transaction(function () use ($userAttributes, $request) {
            $newUser = User::create($userAttributes);

            $parts = explode(' ', $request->name, 2);
            $prenom = $parts[0] ?? $request->name;
            $nom = $parts[1] ?? 'Patient';
            $cleanEmail = strtolower(trim($request->email));

            // Détacher tout éventuel enregistrement patient pointant sur ce user_id
            Patient::where('user_id', $newUser->id)->update(['user_id' => null]);

            // Sécurité anti-conflit : vérifier si un dossier patient existe déjà avec cet email
            // (ex: compte utilisateur précédemment supprimé mais dossier clinique archivé, ou patient pré-enregistré)
            $existingPatient = Patient::where('email', $cleanEmail)
                ->orWhere('email', $request->email)
                ->first();

            if ($existingPatient) {
                $existingPatient->update([
                    'user_id' => $newUser->id,
                    'prenom' => $existingPatient->prenom ?: $prenom,
                    'nom' => $existingPatient->nom ?: $nom,
                    'email' => $cleanEmail,
                ]);
            } else {
                Patient::create([
                    'user_id' => $newUser->id,
                    'prenom' => $prenom,
                    'nom' => $nom,
                    'email' => $cleanEmail,
                ]);
            }

            return $newUser;
        });

        // Envoi sécurisé de la notification OTP (ne crashe jamais l'inscription si problème SMTP temporaire)
        if ($hasOtpColumns) {
            try {
                $user->notify(new OtpVerificationNotification($otp));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        if ($hasOtpColumns && $user->hasPendingOtp()) {
            return redirect()->route('otp.verify');
        }

        return redirect()->route('dashboard');
    }
}

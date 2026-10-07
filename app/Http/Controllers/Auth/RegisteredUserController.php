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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'patient',
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(15),
            'email_verified_at' => null,
        ]);

        $parts = explode(' ', $request->name, 2);
        Patient::create([
            'user_id' => $user->id,
            'prenom' => $parts[0] ?? $request->name,
            'nom' => $parts[1] ?? 'Patient',
            'email' => $request->email,
        ]);

        try {
            $user->notify(new OtpVerificationNotification($otp));
        } catch (\Throwable $e) {
            report($e);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('otp.verify');
    }
}

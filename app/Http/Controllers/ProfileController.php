<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->patient) {
            $parts = explode(' ', $user->name, 2);
            $emailCollision = \App\Models\Patient::where('email', $user->email)
                ->where('id', '!=', $user->patient->id)
                ->exists();

            $updateData = [
                'prenom' => $parts[0] ?? $user->name,
                'nom' => $parts[1] ?? ($user->patient->nom ?: 'Patient'),
            ];
            if (!$emailCollision) {
                $updateData['email'] = $user->email;
            }
            $user->patient->update($updateData);
        } elseif ($user->medecin) {
            $parts = explode(' ', $user->name, 2);
            $emailCollision = \App\Models\Medecin::where('email', $user->email)
                ->where('id', '!=', $user->medecin->id)
                ->exists();

            $updateData = [
                'prenom' => $parts[0] ?? $user->name,
                'nom' => $parts[1] ?? ($user->medecin->nom ?: 'Médecin'),
            ];
            if (!$emailCollision) {
                $updateData['email'] = $user->email;
            }
            $user->medecin->update($updateData);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            $patient = $user->patient ?: \App\Models\Patient::where('email', $user->email)->first();
            if ($patient) {
                $hasMedicalRecords = $patient->resultats()->exists() 
                    || $patient->commandes()->exists() 
                    || $patient->rendezVous()->exists();

                if (!$hasMedicalRecords) {
                    $patient->delete();
                } else {
                    // Conserver les archives médicales légales mais dissocier le compte d'authentification
                    $patient->update(['user_id' => null]);
                }
            }

            $medecin = $user->medecin ?: \App\Models\Medecin::where('email', $user->email)->first();
            if ($medecin) {
                $medecin->update(['user_id' => null]);
            }

            $user->delete();
        });

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

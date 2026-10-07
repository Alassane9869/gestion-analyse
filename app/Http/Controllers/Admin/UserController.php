<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medecin;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $role = trim((string) $request->input('role'));

        $users = User::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($role, ['admin', 'medecin', 'patient'], true), function ($query) use ($role): void {
                $query->where('role', $role);
            })
            ->with(['medecin', 'patient'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'roleFilter' => $role,
            'totalCount' => User::count(),
            'adminsCount' => User::where('role', 'admin')->count(),
            'medecinsCount' => User::where('role', 'medecin')->count(),
            'patientsCount' => User::where('role', 'patient')->count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.form', [
            'user' => new User(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,medecin,patient'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'specialite' => ['nullable', 'string', 'max:100'],
            'groupe_sanguin' => ['nullable', 'string', 'max:10'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Synchronisation automatique selon le rôle choisi
        if ($validated['role'] === 'medecin') {
            $parts = explode(' ', $validated['name'], 2);
            Medecin::create([
                'user_id' => $user->id,
                'prenom' => $parts[0] ?? $validated['name'],
                'nom' => $parts[1] ?? 'Médecin',
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'specialite' => $validated['specialite'] ?? 'Biologie médicale',
            ]);
        } elseif ($validated['role'] === 'patient') {
            $parts = explode(' ', $validated['name'], 2);
            Patient::create([
                'user_id' => $user->id,
                'prenom' => $parts[0] ?? $validated['name'],
                'nom' => $parts[1] ?? 'Patient',
                'email' => $validated['email'],
                'telephone' => $validated['telephone'] ?? null,
                'whatsapp_phone' => $validated['telephone'] ?? null,
                'groupe_sanguin' => $validated['groupe_sanguin'] ?? null,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "L'utilisateur {$user->name} a été créé avec succès.");
    }

    public function edit(User $user): View
    {
        $user->load(['medecin', 'patient']);

        return view('admin.users.form', [
            'user' => $user,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:admin,medecin,patient'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'specialite' => ['nullable', 'string', 'max:100'],
            'groupe_sanguin' => ['nullable', 'string', 'max:10'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Mettre à jour ou créer la fiche associée
        if ($validated['role'] === 'medecin') {
            $parts = explode(' ', $validated['name'], 2);
            if ($user->medecin) {
                $user->medecin->update([
                    'prenom' => $parts[0] ?? $validated['name'],
                    'nom' => $parts[1] ?? ($user->medecin->nom ?: 'Médecin'),
                    'email' => $validated['email'],
                    'telephone' => $validated['telephone'] ?? $user->medecin->telephone,
                    'specialite' => $validated['specialite'] ?? $user->medecin->specialite,
                ]);
            } else {
                Medecin::create([
                    'user_id' => $user->id,
                    'prenom' => $parts[0] ?? $validated['name'],
                    'nom' => $parts[1] ?? 'Médecin',
                    'email' => $validated['email'],
                    'telephone' => $validated['telephone'] ?? null,
                    'specialite' => $validated['specialite'] ?? 'Biologie médicale',
                ]);
            }
        } elseif ($validated['role'] === 'patient') {
            $parts = explode(' ', $validated['name'], 2);
            if ($user->patient) {
                $user->patient->update([
                    'prenom' => $parts[0] ?? $validated['name'],
                    'nom' => $parts[1] ?? ($user->patient->nom ?: 'Patient'),
                    'email' => $validated['email'],
                    'telephone' => $validated['telephone'] ?? $user->patient->telephone,
                    'whatsapp_phone' => $validated['telephone'] ?? $user->patient->whatsapp_phone,
                    'groupe_sanguin' => $validated['groupe_sanguin'] ?? $user->patient->groupe_sanguin,
                ]);
            } else {
                Patient::create([
                    'user_id' => $user->id,
                    'prenom' => $parts[0] ?? $validated['name'],
                    'nom' => $parts[1] ?? 'Patient',
                    'email' => $validated['email'],
                    'telephone' => $validated['telephone'] ?? null,
                    'whatsapp_phone' => $validated['telephone'] ?? null,
                    'groupe_sanguin' => $validated['groupe_sanguin'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.users.index')->with('success', "Le profil de {$user->name} a été mis à jour.");
    }

    public function destroy(User $user): RedirectResponse
    {
        // Empêcher l'administrateur de supprimer son propre compte
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'Vous ne pouvez pas supprimer votre propre compte administrateur.']);
        }

        $nom = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', "L'utilisateur {$nom} a été supprimé du système.");
    }
}

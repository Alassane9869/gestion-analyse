<?php

namespace App\Http\Requests\Medecin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isMedecin() || $this->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email', 'unique:patients,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'groupe_sanguin' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'whatsapp_phone' => ['required', 'string', 'max:25', 'regex:/^\+?[0-9\s\-\.]{7,20}$/'],
            'date_naissance' => ['nullable', 'date', 'before_or_equal:today'],
            'sexe' => ['nullable', 'string', 'max:20'],
            'adresse' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => 'L’adresse e-mail est obligatoire.',
            'email.email' => 'L’adresse e-mail doit être valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'groupe_sanguin.in' => 'Le groupe sanguin sélectionné est invalide.',
            'whatsapp_phone.required' => 'Le numéro de téléphone est obligatoire.',
            'whatsapp_phone.regex' => 'Le numéro de téléphone doit être un format valide (ex: +223 70 00 00 00 ou +33 6 12 34 56 78).',
            'date_naissance.date' => 'La date de naissance est invalide.',
            'date_naissance.before_or_equal' => 'La date de naissance ne peut pas être dans le futur.',
            'sexe.in' => 'Le sexe sélectionné doit être M ou F.',
        ];
    }
}
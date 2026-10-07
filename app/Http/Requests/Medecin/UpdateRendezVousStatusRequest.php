<?php

namespace App\Http\Requests\Medecin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRendezVousStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isMedecin() || $this->user()->isAdmin());
    }

    public function rules(): array
    {
        return ['statut' => ['required', 'in:accepte,refuse']];
    }

    public function messages(): array
    {
        return [
            'statut.required' => 'Veuillez choisir une décision.',
            'statut.in' => 'La décision doit être accepter ou refuser.',
        ];
    }
}
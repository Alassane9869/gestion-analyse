<?php

namespace App\Http\Requests\Medecin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAnalyseCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && ($this->user()->isMedecin() || $this->user()->isAdmin());
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', 'in:en_attente,en_cours,completee'],
            'valeur' => ['required_if:statut,completee', 'nullable', 'numeric'],
            'unite' => ['nullable', 'string', 'max:50'],
            'remarques' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'statut.required' => 'Le statut est obligatoire.',
            'statut.in' => 'Le statut sélectionné est invalide.',
            'valeur.required_if' => 'Saisissez un résultat avant de valider l’analyse.',
            'valeur.numeric' => 'Le résultat doit être une valeur numérique.',
            'unite.max' => 'L’unité ne peut pas dépasser 50 caractères.',
            'remarques.max' => 'Les remarques ne peuvent pas dépasser 5000 caractères.',
        ];
    }
}
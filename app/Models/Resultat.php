<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resultat extends Model
{
    protected function casts(): array
    {
        return [
            'valeur' => 'decimal:2',
            'date_resultat' => 'date',
            'whatsapp_sent_at' => 'datetime',
        ];
    }

    protected $fillable = [
        'patient_id',
        'analyse_id',
        'type_analyse_id',
        'medecin_id',
        'valeur',
        'unite',
        'date_resultat',
        'statut',
        'remarques',
        'whatsapp_message_id',
        'whatsapp_error',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function analyse(): BelongsTo
    {
        return $this->belongsTo(Analyse::class);
    }

    public function typeAnalyse(): BelongsTo
    {
        return $this->belongsTo(TypeAnalyse::class);
    }

    public function medecin(): BelongsTo
    {
        return $this->belongsTo(Medecin::class);
    }
}

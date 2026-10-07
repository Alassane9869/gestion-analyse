<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medecin extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'specialite',
        'adresse',
        'user_id',
    ];

    public function resultats(): HasMany
    {
        return $this->hasMany(Resultat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }
}

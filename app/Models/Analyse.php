<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Analyse extends Model
{
    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'duree_minute' => 'integer',
        ];
    }

    protected $fillable = [
        'code',
        'nom',
        'description',
        'unite',
        'prix',
        'duree_minute',
    ];

    public function resultats(): HasMany
    {
        return $this->hasMany(Resultat::class);
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeAnalyse extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'nom', 'description', 'prix', 'unite', 'duree_minute', 'actif'];

    protected function casts(): array
    {
        return ['prix' => 'decimal:2', 'duree_minute' => 'integer', 'actif' => 'boolean'];
    }

    public function commandes(): BelongsToMany
    {
        return $this->belongsToMany(CommandeAnalyse::class, 'commande_type_analyse')->withPivot('prix_unitaire')->withTimestamps();
    }

    public function resultats(): HasMany
    {
        return $this->hasMany(Resultat::class);
    }
}

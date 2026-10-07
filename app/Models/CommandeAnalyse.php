<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CommandeAnalyse extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'total', 'statut'];

    protected function casts(): array
    {
        return ['total' => 'decimal:2'];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(TypeAnalyse::class, 'commande_type_analyse')->withPivot('prix_unitaire')->withTimestamps();
    }

    public function typesAnalyses(): BelongsToMany
    {
        return $this->types();
    }
}

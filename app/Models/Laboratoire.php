<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Laboratoire extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
        'description',
    ];

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function analyses(): HasMany
    {
        return $this->hasMany(Analyse::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'governorate_id'];

    //relations
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    public function healthCenters(): HasMany
    {
        return $this->hasMany(HealthCenter::class);
    }
}


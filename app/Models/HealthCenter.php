<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'city_id',
        'latitude',
        'longitude',
        'working_hours',
        'available_doses',
        'registration_number',
        'is_active'
    ];

    protected $casts = [
        'working_hours' => 'array',
    ];

    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function drugs(): BelongsToMany
    {
        return $this->belongsToMany(Drug::class, 'health_center_drugs')
            ->withPivot('availability', 'stock')
            ->withTimestamps();
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function vaccines()
    {
        return $this->belongsToMany(Vaccine::class)->withPivot('availability');
    }
}

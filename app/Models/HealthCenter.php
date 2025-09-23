<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'governorate_id',
        'city_id',
        'latitude',
        '
        longitude',
        'working_hours',
        'available_doses',
        'registration_number',
        'is_active'
    ];

    protected $casts = [
        'working_hours' => 'array',
    ];

    public function governorate()
    {
        return $this->belongsTo(Governorate::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function drugs()
    {
        return $this->belongsToMany(Drug::class, 'health_center_drugs')
            ->withPivot('availability', 'stock')
            ->withTimestamps();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

<?php

namespace App\Models;

use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory, HasFilters;

    protected $fillable = [
        'name',
        'description',
        'age_months_min',
        'age_months_max',
        'doses_required',
        'interval_days',
        'side_effects',
        'precautions',
        'is_active'
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function healthCenters()
    {
        return $this->belongsToMany(HealthCenter::class)
            ->withPivot('availability')
            ->withTimestamps(); // <<< ده مهم
    }


    //scopes
    public function scopeAge($query, $age)
    {
        return $query->where('age_months_min', '<=', $age)
            ->where('age_months_max', '>=', $age);
    }

    public function scopeSearch($query, $search)
    {
        return $query->when(
            $search,
            fn($q) =>
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
        );
    }

    public function scopeAvailability($query, $availability)
    {
        return $query->when(
            isset($availability),
            fn($q) =>
            $q->where('availability', $availability)
        );
    }

}

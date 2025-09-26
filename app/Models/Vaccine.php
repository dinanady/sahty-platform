<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

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
        return $this->belongsToMany(HealthCenter::class)->withPivot('availability');
    }

    //scopes
    public function scopeFilterByAge($query, $age)
    {
        return $query->where('age_months_min', '<=', $age)
            ->where('age_months_max', '>=', $age);
    }
}

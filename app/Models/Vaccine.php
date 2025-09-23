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
}

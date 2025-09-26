<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'national_id',
        'child_name',
        'child_birth_date',
        'vaccine_id',
        'health_center_id',
        'appointment_date',
        'appointment_time',
        'status',
        'dose_number',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function healthCenter()
    {
        return $this->belongsTo(HealthCenter::class);
    }
}

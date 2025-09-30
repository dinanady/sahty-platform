<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorScheduleException extends Model
{
    use HasFactory;
    protected $fillable = [
        'doctor_id',
        'exception_date',
        'type',
        'reason'
    ];

    protected $casts = [
        'exception_date' => 'date'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty',
        'phone',
        'health_center_id',
        'is_active'
    ];

    public function healthCenter()
    {
        return $this->belongsTo(HealthCenter::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function overrides()
    {
        return $this->hasMany(DoctorScheduleOverride::class);
    }

    public function getScheduleForDate($date)
    {
        $override = $this->overrides()->where('date', $date)->first();
        if ($override)
            return $override;

        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        return $this->schedules()->where('day_of_week', $dayOfWeek)->get();
    }
}

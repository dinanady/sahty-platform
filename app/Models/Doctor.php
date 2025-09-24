<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function healthCenter(): BelongsTo
    {
        return $this->belongsTo(HealthCenter::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function overrides(): HasMany
    {
        return $this->hasMany(DoctorScheduleOverride::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(DoctorSpecialty::class, 'doctor_specialty_id');
    }

    //Methods
    public function getScheduleForDate($date)
    {
        $override = $this->overrides()->where('date', $date)->get();

        if ($override->isNotEmpty()) {
            return $override; // Collection حتى لو عنصر واحد
        }

        $dayOfWeek = strtolower(Carbon::parse($date)->format('l'));
        return $this->schedules()->where('day_of_week', $dayOfWeek)->get();
    }

}

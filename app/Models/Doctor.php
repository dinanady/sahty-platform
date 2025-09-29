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

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function exceptions()
    {
        return $this->hasMany(DoctorScheduleException::class);
    }

    public function isAvailableOn($date)
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = strtolower($carbonDate->format('l')); // saturday, sunday...

        $exception = $this->exceptions()
            ->where('exception_date', $carbonDate->format('Y-m-d'))
            ->first();

        if ($exception) {
            return $exception->type === 'available';
        }

        return $this->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('available', true)
            ->exists();
    }
}

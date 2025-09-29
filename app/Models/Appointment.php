<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'child_name',
        'national_id',
        'child_birth_date',
        'vaccine_id',
        'health_center_id',
        'appointment_date',
        'appointment_time',
        'status',
        'dose_number',
        'notes'
    ];

    protected $casts = [
        'child_birth_date' => 'date:Y-m-d',
        'appointment_date' => 'date:Y-m-d',
        'appointment_time' => 'datetime:H:i',
    ];

    /**
     * العلاقة مع جدول اللقاحات
     */
    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }

    /**
     * العلاقة مع جدول المراكز الصحية
     */
    public function healthCenter()
    {
        return $this->belongsTo(HealthCenter::class);
    }

    /**
     * سكوب للفلترة حسب المركز الصحي للمستخدم
     */
    public function scopeForUserHealthCenter($query)
    {
        if (auth()->check() && auth()->user()->health_center_id) {
            return $query->where('health_center_id', auth()->user()->health_center_id);
        }
        return $query;
    }

    /**
     * سكوب للبحث باسم الطفل
     */
    public function scopeByChildName($query, $name)
    {
        if ($name) {
            return $query->where('child_name', 'like', '%' . $name . '%');
        }
        return $query;
    }

    /**
     * سكوب للبحث بالرقم القومي
     */
    public function scopeByNationalId($query, $nationalId)
    {
        if ($nationalId) {
            return $query->where('national_id', 'like', '%' . $nationalId . '%');
        }
        return $query;
    }

    /**
     * سكوب للفلترة بنوع اللقاح
     */
    public function scopeByVaccine($query, $vaccineId)
    {
        if ($vaccineId) {
            return $query->where('vaccine_id', $vaccineId);
        }
        return $query;
    }

    /**
     * سكوب للفلترة بالحالة
     */
    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * سكوب للفلترة بتاريخ الموعد
     */
    public function scopeByAppointmentDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('appointment_date', $date);
        }
        return $query;
    }

    /**
     * سكوب للفلترة بتاريخ ميلاد الطفل
     */
    public function scopeByChildBirthDate($query, $date)
    {
        if ($date) {
            return $query->whereDate('child_birth_date', $date);
        }
        return $query;
    }

    /**
     * سكوب للفلترة برقم الجرعة
     */
    public function scopeByDoseNumber($query, $doseNumber)
    {
        if ($doseNumber) {
            return $query->where('dose_number', $doseNumber);
        }
        return $query;
    }
}

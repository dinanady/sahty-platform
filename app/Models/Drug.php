<?php

namespace App\Models;

use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory, HasFilters;

    protected $fillable = [
        'name',
        'scientific_name',
        'description',
        'manufacturer',
        'price',
        'insurance_covered',
        'category',
        'dosage_form',
        'approval_status',
        'submitted_by_center_id',
        'is_government_approved',
        'approved_at',
        'approved_by',
        'is_active'
    ];

    public $casts = [
        'insurance_covered' => 'boolean',
        'is_government_approved' => 'boolean',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'];

    public function healthCenters()
    {
        return $this->belongsToMany(HealthCenter::class, 'health_center_drugs')
            ->withPivot(['stock', 'availability', 'created_at', 'updated_at'])
            ->withTimestamps();
    }

    public function submittedByCenter()
    {
        return $this->belongsTo(HealthCenter::class, 'submitted_by_center_id');
    }

    //scopes
    public function scopeFilterByHealthCenter($query, $healthCenterId = null)
    {
        if ($healthCenterId) {
            return $query->whereHas('healthCenters', function ($q) use ($healthCenterId) {
                $q->where('health_center_id', $healthCenterId);
            })->with([
                        'healthCenters' => function ($q) use ($healthCenterId) {
                            $q->where('health_center_id', $healthCenterId);
                        }
                    ]);
        }

        return $query;
    }

    public function scopeGovernmentApproved($query)
    {
        return $query->where('is_government_approved', true);
    }

    public function scopeCenterSubmitted($query)
    {
        return $query->where('submitted_by_center_id', '!=', null);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('scientific_name', 'like', "%{$search}%");
        });
    }

    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeAvailability($query, $availability)
    {
        return $query->whereHas('healthCenters', function ($q) use ($availability) {
            $q->where('health_center_drugs.availability', $availability);
        });
    }
}

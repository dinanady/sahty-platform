<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'scientific_name',
        'description',
        'manufacturer',
        'price',
        'insurance_covered',
        'category',
        'dosage_form',
        'is_active'
    ];

    public function healthCenters()
    {
        return $this->belongsToMany(HealthCenter::class, 'health_center_drugs')
            ->withPivot(['availability', 'stock']);
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

}

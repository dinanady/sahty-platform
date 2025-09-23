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
        return $this->belongsToMany(HealthCenter::class, 'health_center_drug')
            ->withPivot(['availability', 'stock']);
    }
}

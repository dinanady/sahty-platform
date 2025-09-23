<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCenterDrug extends Model
{
    use HasFactory;
    protected $fillable = [
        'health_center_id',
        'drug_id',
        'availability',
        'stock',
    ];

}

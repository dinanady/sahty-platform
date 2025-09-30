<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Governorate extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

public function cities()
{
    return $this->hasMany(City::class);
}

public function healthCenters()
{
    return $this->hasManyThrough(
        HealthCenter::class,
        City::class,
        'governorate_id',
        'city_id'
    );
}

}

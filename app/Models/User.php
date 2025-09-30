<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'national_id',
        'role',
        'preferred_contact',
        'is_verified',
        'password',
        'health_center_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'password' => 'hashed',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // في User Model
public function healthCenter() {
    return $this->belongsTo(HealthCenter::class, 'health_center_id');
}

// في HealthCenter Model
public function manager() {
    return $this->hasOne(User::class, 'health_center_id')
        ->where('role', 'health_center_manager');
}

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isHealthCenterManager()
    {
        return $this->role === 'health_center_manager';
    }
}

<?php

namespace App\Models;

use App\Traits\HasFilters;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles, HasFilters;

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
        'created_at' => 'datetime',
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

    //scopes
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%");
        });
    }

    public function scopeRole($query, $role)
    {
        return $query->when($role, function ($q) use ($role) {
            $q->whereHas('roles', fn($qr) => $qr->where('id', $role)->orWhere('name', $role));
        });
    }

    public function scopePermission($query, $permission)
    {
        return $query->when($permission, function ($q) use ($permission) {
            $q->whereHas('roles.permissions', fn($qr) => $qr->where('id', $permission)->orWhere('name', $permission));
        });
    }

    public function scopeStatus($query, $status)
    {
        return $query->when(!is_null($status), function ($q) use ($status) {
            $q->where('is_active', $status);
        });
    }

    public function scopeWithoutRoles($query, $withoutRoles)
    {
        return $query->when($withoutRoles, function ($q) {
            $q->whereDoesntHave('roles');
        });
    }
}

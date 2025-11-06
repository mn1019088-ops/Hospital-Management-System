<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Reception extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'reception';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    public function handledAppointments()
    {
        return $this->hasMany(Appointment::class, 'handled_by');
    }

    public function registeredPatients()
    {
        return $this->hasMany(Patient::class, 'registered_by');
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'userable');
    }
}
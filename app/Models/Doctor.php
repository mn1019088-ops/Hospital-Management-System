<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'doctor';

    protected $fillable = [
        'doctor_id',
        'name',
        'email',
        'password',
        'phone',
        'specialization',
        'qualification',
        'experience_years',
        'address',
        'license_number',
        'consultation_fee',
        'avatar',
        'is_active',
        'available_from',
        'available_to',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'consultation_fee' => 'decimal:2',
        'experience_years' => 'integer',
        'available_from' => 'datetime:H:i',
        'available_to' => 'datetime:H:i',
        'email_verified_at' => 'datetime',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'head_doctor_id');
    }

    public function getFullNameAttribute()
    {
        return 'Dr. ' . $this->name;
    }

    public function getIsAvailableAttribute()
    {
        return $this->is_active;
    }

    public function activities()
    {
        return $this->morphMany(ActivityLog::class, 'userable');
    }
}
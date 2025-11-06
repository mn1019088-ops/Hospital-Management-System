<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'appointment_type',
        'reason',
        'status',
        'fee',
        'notes',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime:H:i',
        'fee' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function getAppointmentDatetimeAttribute()
    {
        return $this->appointment_date->format('M d, Y') . ' at ' . 
               \Carbon\Carbon::parse($this->appointment_time)->format('h:i A');
    }

    public function getIsUpcomingAttribute()
    {
        return $this->appointment_date >= now()->format('Y-m-d') && 
               in_array($this->status, ['scheduled', 'confirmed']);
    }

    public function getIsTodayAttribute()
    {
        return $this->appointment_date->isToday();
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('appointment_date', '>=', today())
                    ->whereIn('status', ['scheduled', 'confirmed']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'scheduled');
    }
}
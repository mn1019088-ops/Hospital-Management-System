<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'max_patients',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_active' => 'boolean',
        'max_patients' => 'integer',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getTimeSlotAttribute()
    {
        return Carbon::parse($this->start_time)->format('h:i A') . ' - ' .
               Carbon::parse($this->end_time)->format('h:i A');
    }

    public function getDayNameAttribute()
    {
        return ucfirst($this->day_of_week);
    }

    public function getIsTodayAttribute()
    {
        return strtolower($this->day_of_week) === strtolower(now()->englishDayOfWeek);
    }

    public function getIsCurrentlyAvailableAttribute()
    {
        if (!$this->is_active || !$this->is_today) {
            return false;
        }

        $currentTime = now()->format('H:i:s');
        return $currentTime >= $this->start_time->format('H:i:s') &&
               $currentTime <= $this->end_time->format('H:i:s');
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', strtolower($day));
    }

    public function scopeForToday($query)
    {
        return $query->where('day_of_week', strtolower(now()->englishDayOfWeek));
    }
}

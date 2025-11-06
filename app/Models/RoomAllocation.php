<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'patient_id',
        'admission_date',
        'discharge_date',
        'reason',
        'status',
        'total_amount',
        'paid_amount',
        'notes',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'discharge_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getDueAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }

    public function getDaysStayedAttribute()
    {
        $endDate = $this->discharge_date ?: now();
        return $this->admission_date->diffInDays($endDate);
    }

    public function getTotalBillAmountAttribute()
    {
        if ($this->room && $this->room->price_per_day) {
            return $this->days_stayed * $this->room->price_per_day;
        }
        return $this->total_amount;
    }

    public function getFormattedAdmissionDateAttribute()
    {
        return $this->admission_date->format('M d, Y');
    }

    public function getFormattedDischargeDateAttribute()
    {
        return $this->discharge_date ? $this->discharge_date->format('M d, Y') : 'Not Discharged';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDischarged($query)
    {
        return $query->where('status', 'discharged');
    }

    public function scopeCurrent($query)
    {
        return $query->where('status', 'active')
                    ->whereNull('discharge_date');
    }
}
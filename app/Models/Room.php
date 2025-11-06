<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'room_type',
        'department_id',
        'floor',
        'capacity',
        'occupied',
        'price_per_day',
        'facilities',
        'status',
        'is_active',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'capacity' => 'integer',
        'occupied' => 'integer',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function allocations()
    {
        return $this->hasMany(RoomAllocation::class);
    }

    public function getIsAvailableAttribute()
    {
        return $this->status === 'available' && $this->occupied < $this->capacity;
    }

    public function getAvailableBedsAttribute()
    {
        return $this->capacity - $this->occupied;
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->capacity == 0) return 0;
        return round(($this->occupied / $this->capacity) * 100, 2);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')
                    ->whereColumn('occupied', '<', 'capacity');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
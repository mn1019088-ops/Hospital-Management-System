<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'head_doctor_id',
        'floor',
        'contact_email',
        'contact_phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function headDoctor()
    {
        return $this->belongsTo(Doctor::class, 'head_doctor_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'specialization', 'name');
    }

    public function getAvailableRoomsAttribute()
    {
        return $this->rooms()->where('status', 'available')->count();
    }

    public function getTotalRoomsAttribute()
    {
        return $this->rooms()->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
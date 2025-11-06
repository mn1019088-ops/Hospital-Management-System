<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'userable_id',
        'userable_type',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'model_type',
        'model_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the parent userable model (Admin, Doctor, Reception, Patient).
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * Get the related model that was acted upon.
     */
    public function model()
    {
        return $this->morphTo();
    }

    // Scope for recent activities
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Scope for specific user type
    public function scopeUserType($query, $type)
    {
        return $query->where('userable_type', $type);
    }

    // Scope for specific action
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }
}
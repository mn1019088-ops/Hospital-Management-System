<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_id',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'visit_date',
        'symptoms',
        'diagnosis',
        'treatment',
        'prescription',
        'tests_recommended',
        'notes',
        'weight',
        'height',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'temperature',
        'heart_rate',
        'respiratory_rate',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'temperature' => 'decimal:2',
        'blood_pressure_systolic' => 'integer',
        'blood_pressure_diastolic' => 'integer',
        'heart_rate' => 'integer',
        'respiratory_rate' => 'integer',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getBloodPressureAttribute()
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return $this->blood_pressure_systolic . '/' . $this->blood_pressure_diastolic;
        }
        return null;
    }

    public function getBmiAttribute()
    {
        if ($this->weight && $this->height && $this->height > 0) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }
        return null;
    }

    public function getBmiCategoryAttribute()
    {
        $bmi = $this->bmi;
        if (!$bmi) return null;

        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obese';
    }

    public function getBloodPressureCategoryAttribute()
    {
        if (!$this->blood_pressure_systolic || !$this->blood_pressure_diastolic) {
            return null;
        }

        $systolic = $this->blood_pressure_systolic;
        $diastolic = $this->blood_pressure_diastolic;

        if ($systolic < 120 && $diastolic < 80) return 'Normal';
        if ($systolic < 130 && $diastolic < 80) return 'Elevated';
        if ($systolic < 140 || $diastolic < 90) return 'Hypertension Stage 1';
        if ($systolic >= 140 || $diastolic >= 90) return 'Hypertension Stage 2';
        if ($systolic > 180 || $diastolic > 120) return 'Hypertensive Crisis';
        
        return 'Unknown';
    }

    public function getHeartRateCategoryAttribute()
    {
        if (!$this->heart_rate) return null;

        if ($this->heart_rate < 60) return 'Bradycardia';
        if ($this->heart_rate > 100) return 'Tachycardia';
        return 'Normal';
    }

    public function getTemperatureCategoryAttribute()
    {
        if (!$this->temperature) return null;

        if ($this->temperature < 36.1) return 'Hypothermia';
        if ($this->temperature > 37.2) return 'Fever';
        return 'Normal';
    }

    public function getRespiratoryRateCategoryAttribute()
    {
        if (!$this->respiratory_rate) return null;

        if ($this->respiratory_rate < 12) return 'Bradypnea';
        if ($this->respiratory_rate > 20) return 'Tachypnea';
        return 'Normal';
    }

    public function getFormattedVisitDateAttribute()
    {
        return $this->visit_date->format('F d, Y');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('visit_date', '>=', now()->subDays($days));
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }
}
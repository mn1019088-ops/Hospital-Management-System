<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool 
    { 
        return true; 
    }

    public function rules(): array
    {
        $appointmentId = $this->route('appointment') ? $this->route('appointment')->id : null;

        return [
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value);
                    if ($date->isWeekend()) {
                        $fail('Appointments cannot be scheduled on weekends.');
                    }
                },
            ],
            'appointment_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $time = Carbon::parse($value);
                    $hour = $time->hour;
                    $minute = $time->minute;
                    
                    if ($hour < 9 || $hour > 17 || ($hour == 17 && $minute > 0)) {
                        $fail('Appointments must be scheduled between 9:00 AM and 5:00 PM.');
                    }
                },
            ],
            'appointment_type' => 'required|in:consultation,checkup,follow-up,emergency',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:scheduled,confirmed,in-progress,completed,cancelled,no-show',
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.required' => 'Please select a doctor.',
            'doctor_id.exists' => 'The selected doctor is invalid.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.date' => 'Please enter a valid date.',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Please enter a valid time format.',
            'appointment_type.required' => 'Please select an appointment type.',
            'appointment_type.in' => 'Please select a valid appointment type.',
            'reason.required' => 'Please provide a reason for the appointment.',
            'reason.max' => 'Reason may not be greater than 255 characters.',
            'notes.max' => 'Notes may not be greater than 255 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Please select a valid status.',
        ];
    }
}
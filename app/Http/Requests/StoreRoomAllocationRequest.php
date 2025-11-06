<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomAllocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required|exists:rooms,id',
            'admission_date' => 'required|date|after_or_equal:today',
            'estimated_stay_days' => 'required|integer|min:1|max:365',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'The selected patient does not exist.',
            'room_id.required' => 'Please select a room.',
            'room_id.exists' => 'The selected room does not exist.',
            'admission_date.required' => 'Admission date is required.',
            'admission_date.after_or_equal' => 'Admission date cannot be in the past.',
            'estimated_stay_days.required' => 'Estimated stay days is required.',
            'estimated_stay_days.integer' => 'Estimated stay days must be a whole number.',
            'estimated_stay_days.min' => 'Estimated stay must be at least 1 day.',
            'estimated_stay_days.max' => 'Estimated stay cannot exceed 365 days.',
            'reason.required' => 'Reason for admission is required.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.min' => 'Total amount must be at least 0.'
        ];
    }

    protected function prepareForValidation()
    {
        // Convert estimated_stay_days to integer if it's a string
        if ($this->has('estimated_stay_days') && is_string($this->estimated_stay_days)) {
            $this->merge([
                'estimated_stay_days' => (int) $this->estimated_stay_days
            ]);
        }
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomAllocationRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->guard('reception')->check();
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required|exists:rooms,id',
            'admission_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'estimated_stay_days' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'Please select a patient.',
            'room_id.required' => 'Please select a room.',
            'admission_date.required' => 'Admission date is required.',
            'reason.required' => 'Reason for admission is required.',
            'estimated_stay_days.required' => 'Estimated stay days are required.',
        ];
    }
}

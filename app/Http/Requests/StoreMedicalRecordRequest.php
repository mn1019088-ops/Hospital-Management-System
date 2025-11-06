<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMedicalRecordRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->guard('doctor')->check();
    }

    public function rules()
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'symptoms' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'treatment' => 'required|string|max:255',
            'prescription' => 'nullable|string|max:255',
            'tests_recommended' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:255',

            'weight' => 'required|numeric|min:0|max:300',
            'height' => 'required|numeric|min:0|max:300',
            'blood_pressure_systolic' => 'required|integer|min:0|max:300',
            'blood_pressure_diastolic' => 'required|integer|min:0|max:200',
            'temperature' => 'required|numeric|min:30|max:200',
            'heart_rate' => 'required|integer|min:30|max:200',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'Please select a patient.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'visit_date.required' => 'Visit date is required.',
            'symptoms.required' => 'Symptoms field is required.',
            'diagnosis.required' => 'Diagnosis field is required.',
            'treatment.required' => 'Treatment field is required.',
        ];
    }
}

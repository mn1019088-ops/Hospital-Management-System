<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $patientId = $this->route('patient')->id;

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['nullable','email', Rule::unique('patients','email')->ignore($patientId)],
            'phone' => 'required|string|max:10|regex:/^[0-9+\-\s()]*$/',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
        ];
    }
}

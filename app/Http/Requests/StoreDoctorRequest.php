<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email',
            'password' => 'nullable|string|min:6|confirmed',
            'phone' => 'required|string|max:10|regex:/^[0-9+\-\s()]*$/',
            'specialization' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'experience_years' => 'required|integer|min:0',
            'consultation_fee' => 'nullable|numeric|min:0',
        ];
    }
}

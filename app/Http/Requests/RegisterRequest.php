<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userType = $this->input('user_type');

        $rules = [
            'user_type' => ['required', Rule::in(['admin', 'doctor', 'reception', 'patient'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required','string','email','max:255', Rule::unique($userType . 's', 'email'),],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'phone' => ['required','nullable','regex:/^[6-9]\d{9}$/', Rule::unique($userType . 's', 'phone'),],
        ];

        if ($userType === 'doctor') {
            $rules = array_merge($rules, [
                'specialization' => ['required', 'string', 'max:255'],
                'qualification' => ['required', 'string', 'max:255'],
                'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            ]);
        }
        if ($this->user_type === 'patient') {
            $rules['date_of_birth'] = 'required|date|before:today';
            $rules['gender'] = 'required|in:male,female,other';
            $rules['address'] = 'nullable|string';
            $rules['blood_group'] = 'nullable|string|max:10';
            $rules['medical_history'] = 'nullable|string';
            $rules['allergies'] = 'nullable|string';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'user_type.required' => 'Please select a valid user type.',
            'user_type.in' => 'Invalid user type selected.',
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email is already registered for this user type.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
            'password.confirmed' => 'Passwords do not match.',

            'phone.regex' => 'Please enter a valid Indian mobile number (starts with 6–9, 10 digits).',
            'phone.unique' => 'This phone number is already registered.',

            'specialization.required' => 'Specialization field is required for doctors.',
            'qualification.required' => 'Qualification is required for doctors.',
            'experience_years.required' => 'Experience years is required.',
            'experience_years.integer' => 'Experience years must be a number.',
        ];
    }
}

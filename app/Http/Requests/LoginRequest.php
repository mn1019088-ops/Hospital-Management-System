<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'user_type' => 'required|in:admin,doctor,reception,patient',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'password.required' => 'Please enter your password.',
            'user_type.required' => 'Please select your role type.',
            'user_type.in' => 'Invalid user type selected.',
        ];
    }
}

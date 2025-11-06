<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceptionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:receptions,email',
            'phone' => 'required|string|max:10|regex:/^[0-9+\-\s()]*$/',
            'password' => 'required|string|min:6',
            'is_active' => 'nullable|boolean',
        ];
    }
}

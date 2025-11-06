<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->route('admin') ? $this->route('admin')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $adminId,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'is_super_admin' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
        ];
    }
}
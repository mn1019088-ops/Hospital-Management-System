<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $receptionId = Auth::guard('reception')->id();

        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:receptions,email,' . $receptionId,
            'phone' => 'nullable|string|max:10|regex:/^[0-9+\-\s()]*$/',
        ];
    }
}

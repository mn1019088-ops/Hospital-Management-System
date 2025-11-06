<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReceptionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $receptionId = $this->route('reception')->id;

        return [
            'name' => 'required|string|max:255',
            'email' => ['required','email',Rule::unique('receptions','email')->ignore($receptionId)],
            'phone' => 'required|string|max:10|regex:/^[0-9+\-\s()]*$/',
            'password' => 'nullable|string|min:6',
            'is_active' => 'required|boolean',
        ];
    }
}

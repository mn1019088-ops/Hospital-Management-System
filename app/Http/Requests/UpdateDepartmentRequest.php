<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','min:2','max:255', Rule::unique('departments', 'name')->ignore($this->route('department')),],
            'description' => 'nullable|string|max:255',
            'floor' => 'nullable|integer|min:1|max:20',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => ['nullable','string','max:15','regex:/^[\d\-\s\(\)\+]+$/'],
            'head_doctor_id' => 'nullable|exists:doctors,id',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The department name is required.',
            'name.unique' => 'This department name is already taken by another department.',
            'contact_phone.regex' => 'Phone must be a valid format (e.g., 123-456-7890).',
            'is_active.boolean' => 'Invalid status value provided.',
        ];
    }
}

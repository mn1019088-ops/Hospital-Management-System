<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DischargeRoomAllocationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'discharge_date' => 'required|date|after_or_equal:admission_date',
            'discharge_notes' => 'nullable|string|max:255',
        ];
    }
}

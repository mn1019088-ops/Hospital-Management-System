<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get the room ID from the route parameter - use 'id' instead of 'room'
        $roomId = $this->route('id');

        return [
            'room_number' => 'required|string|max:20|unique:rooms,room_number,' . $roomId,
            'room_type' => 'required|string|in:general,private,semi-private,deluxe,icu,operation,emergency,maternity,recovery',
            'department_id' => 'required|exists:departments,id',
            'ward' => 'nullable|string|max:50',
            'floor' => 'required|integer|min:1|max:50',
            'capacity' => 'required|integer|min:1|max:20',
            'price_per_day' => 'required|numeric|min:0|max:100000',
            'status' => 'required|in:available,occupied,maintenance,cleaning',
            'facilities' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'room_number.required' => 'Room number is required',
            'room_number.unique' => 'This room number already exists',
            'room_type.required' => 'Room type is required',
            'department_id.required' => 'Department is required',
            'department_id.exists' => 'Selected department does not exist',
            'floor.required' => 'Floor number is required',
            'capacity.required' => 'Capacity is required',
            'price_per_day.required' => 'Price per day is required',
            'status.required' => 'Status is required',
        ];
    }
}
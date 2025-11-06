<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
        return [
            'room_number' => 'required|string|max:20|unique:rooms,room_number',
            'room_type' => 'required|string|in:general,private,semi-private,deluxe,icu,operation,emergency,maternity,recovery',
            'department_id' => 'required|exists:departments,id',
            'floor' => 'required|integer|min:1|max:50',
            'capacity' => 'required|integer|min:1|max:20',
            'price_per_day' => 'required|numeric|min:0|max:100000',
            'status' => 'required|in:available,occupied,maintenance,cleaning',
            'facilities' => 'nullable|string|max:500'
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'room_number.required' => 'Room number is required',
            'room_number.unique' => 'This room number already exists',
            'room_number.max' => 'Room number cannot exceed 20 characters',
            
            'room_type.required' => 'Room type is required',
            'room_type.in' => 'Please select a valid room type',
            
            'department_id.required' => 'Department selection is required',
            'department_id.exists' => 'Selected department does not exist',
            
            'floor.required' => 'Floor number is required',
            'floor.integer' => 'Floor must be a number',
            'floor.min' => 'Floor number must be at least 1',
            'floor.max' => 'Floor number cannot exceed 50',
            
            'capacity.required' => 'Room capacity is required',
            'capacity.integer' => 'Capacity must be a number',
            'capacity.min' => 'Capacity must be at least 1 patient',
            'capacity.max' => 'Capacity cannot exceed 20 patients',
            
            'price_per_day.required' => 'Price per day is required',
            'price_per_day.numeric' => 'Price must be a valid number',
            'price_per_day.min' => 'Price cannot be negative',
            'price_per_day.max' => 'Price cannot exceed 100,000',
            
            'status.required' => 'Room status is required',
            'status.in' => 'Please select a valid status',
            
            'facilities.max' => 'Facilities description cannot exceed 500 characters',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'room_number' => 'room number',
            'room_type' => 'room type',
            'department_id' => 'department',
            'price_per_day' => 'price per day',
        ];
    }
}
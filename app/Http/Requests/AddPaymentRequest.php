<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
        ];
    }
}

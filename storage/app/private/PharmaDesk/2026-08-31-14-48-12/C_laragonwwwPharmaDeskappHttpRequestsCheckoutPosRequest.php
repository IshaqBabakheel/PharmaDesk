<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutPosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
            ],
            'sale_date' => [
                'required',
                'date',
            ],
            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'shipping' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'other_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in(['receipt', 'payment']),
            ],

            'sale_id' => [
                'nullable',
                'integer',
                'exists:sales,id',
            ],

            'purchase_id' => [
                'nullable',
                'integer',
                'exists:purchases,id',
            ],

            'customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
            ],

            'supplier_id' => [
                'nullable',
                'integer',
                'exists:suppliers,id',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'method' => [
                'required',
                Rule::in([
                    'cash',
                    'card',
                    'bank_transfer',
                    'jazzcash',
                    'easypaisa',
                    'other',
                ]),
            ],

            'payment_date' => [
                'required',
                'date',
            ],

            'reference_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Payment type is required.',
            'type.in' => 'Invalid payment type.',

            'amount.required' => 'Payment amount is required.',
            'amount.gt' => 'Payment amount must be greater than zero.',

            'method.required' => 'Payment method is required.',
            'payment_date.required' => 'Payment date is required.',
        ];
    }
}
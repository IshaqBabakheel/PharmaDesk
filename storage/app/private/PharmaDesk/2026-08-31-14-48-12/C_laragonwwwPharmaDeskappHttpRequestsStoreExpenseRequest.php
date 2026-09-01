<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_category_id' => [
                'required',
                'exists:expense_categories,id',
            ],

            'expense_date' => [
                'required',
                'date',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'paid_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in([
                    'Completed',
                    'Draft',
                    'Cancelled',
                ]),
            ],

            'payment_method' => [
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


            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }
}
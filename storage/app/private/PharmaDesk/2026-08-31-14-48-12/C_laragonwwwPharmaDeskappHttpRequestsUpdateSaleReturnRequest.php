<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sale_id' => [
                'required',
                'integer',
                'exists:sales,id',
            ],

            'customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
            ],

            'return_date' => [
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

            'other_charges' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:draft,completed,cancelled',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.sale_item_id' => [
                'required',
                'integer',
                'exists:sale_items,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'items.*.free_quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'items.*.discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],
        ];
    }
}

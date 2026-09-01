<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockAdjustmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Header
            |--------------------------------------------------------------------------
            */

            'type' => [
                'required',
                Rule::in([
                    'increase',
                    'decrease',
                ]),
            ],

            'adjustment_date' => [
                'required',
                'date',
            ],

            'reason' => [
                'required',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'completed',
                ]),
            ],

            /*
            |--------------------------------------------------------------------------
            | Items
            |--------------------------------------------------------------------------
            */

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.medicine_id' => [
                'required',
                'integer',
                'exists:medicines,id',
            ],

            'items.*.purchase_item_id' => [
                'nullable',
                'integer',
                'exists:purchase_items,id',
            ],

            'items.*.batch_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'items.*.expiry_date' => [
                'nullable',
                'date',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.selling_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'items.*.notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'type.required' =>
                'Adjustment type is required.',

            'adjustment_date.required' =>
                'Adjustment date is required.',

            'reason.required' =>
                'Adjustment reason is required.',

            'items.required' =>
                'At least one adjustment item is required.',

            'items.min' =>
                'At least one adjustment item is required.',

            'items.*.medicine_id.required' =>
                'Please select a medicine.',

            'items.*.medicine_id.exists' =>
                'The selected medicine does not exist.',

            'items.*.purchase_item_id.exists' =>
                'The selected stock batch does not exist.',

            'items.*.quantity.required' =>
                'Adjustment quantity is required.',

            'items.*.quantity.min' =>
                'Adjustment quantity must be greater than zero.',
        ];
    }
}
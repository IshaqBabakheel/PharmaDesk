<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleReturnRequest extends FormRequest
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
            | Sale
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Return Amounts
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            'status' => [
                'required',
                'in:draft,completed,cancelled',
            ],

            'notes' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Return Items
            |--------------------------------------------------------------------------
            */

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

        ];
    }

    public function messages(): array
    {
        return [

            'sale_id.required' =>
                'Please select a sale.',

            'sale_id.exists' =>
                'The selected sale does not exist.',

            'return_date.required' =>
                'Return date is required.',

            'items.required' =>
                'Sale return must contain at least one item.',

            'items.min' =>
                'Sale return must contain at least one item.',

            'items.*.sale_item_id.required' =>
                'Sale item is required.',

            'items.*.sale_item_id.exists' =>
                'The selected sale item does not exist.',

            'items.*.quantity.required' =>
                'Return quantity is required.',

            'items.*.quantity.min' =>
                'Return quantity cannot be negative.',

            'items.*.free_quantity.min' =>
                'Return free quantity cannot be negative.',
        ];
    }
}
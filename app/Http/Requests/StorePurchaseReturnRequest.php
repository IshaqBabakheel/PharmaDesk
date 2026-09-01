<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseReturnRequest extends FormRequest
{
    /**
     * Authorize the request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'purchase_id' => [
                'required',
                'exists:purchases,id',
            ],

            'supplier_id' => [
                'required',
                'exists:suppliers,id',
            ],

            'return_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:Completed,Draft,Cancelled',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'subtotal' => [
                'required',
                'numeric',
                'min:0',
            ],

            'discount_type' => [
                'required',
                'in:Fixed,Percentage',
            ],

            'discount' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'tax_type' => [
                'required',
                'in:Fixed,Percentage',
            ],

            'tax' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'grand_total' => [
                'required',
                'numeric',
                'min:0',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.purchase_item_id' => [
                'required',
                'exists:purchase_items,id',
            ],

            'items.*.medicine_id' => [
                'required',
                'exists:medicines,id',
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
                'required',
                'numeric',
                'min:0',
            ],

            'items.*.total' => [
                'required',
                'numeric',
                'min:0',
            ],

        ];
    }

    /**
     * Validation Messages
     */
    public function messages(): array
    {
        return [

            'purchase_id.required' => 'Please select a purchase.',

            'supplier_id.required' => 'Please select a supplier.',

            'return_date.required' => 'Return date is required.',

            'items.required' => 'Please add at least one medicine.',

            'items.min' => 'At least one medicine is required.',

            'items.*.medicine_id.required' => 'Please select a medicine.',

            'items.*.quantity.required' => 'Quantity is required.',

            'items.*.quantity.min' => 'Quantity must be greater than zero.',

            'items.*.purchase_price.required' => 'Purchase price is required.',

        ];
    }
}
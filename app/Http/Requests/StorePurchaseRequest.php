<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Authorize request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('purchases.create');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set default values for discount_type and tax_type
        $this->merge([
            'discount_type' => $this->input('discount_type', 'Fixed'),
            'tax_type' => $this->input('tax_type', 'Percentage'),
        ]);
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'supplier_id' => ['required', 'exists:suppliers,id'],

            'purchase_date' => ['required', 'date'],

            'subtotal' => ['required', 'numeric'],

            'invoice_number' => ['nullable', 'string', 'max:100'],

            'reference_number' => ['nullable', 'string', 'max:100'],

            'discount_type' => ['required', 'in:Fixed,Percentage'],

            'discount' => ['nullable', 'numeric', 'min:0'],

            'tax_type' => ['required', 'in:Fixed,Percentage'],

            'tax' => ['nullable', 'numeric', 'min:0'],

            'shipping' => ['nullable', 'numeric', 'min:0'],

            'other_charges' => ['nullable', 'numeric', 'min:0'],

            'grand_total' => ['required', 'numeric'],

            'paid_amount' => ['nullable', 'numeric', 'min:0'],

            'due_amount' => ['nullable', 'numeric', 'min:0'],

            'payment_status' => ['required', 'in:Paid,Partially Paid,Unpaid'],

            'status' => ['required', 'in:Draft,Completed,Cancelled'],

            'notes' => ['nullable', 'string'],

            /*
            |--------------------------------------------------------------------------
            | Purchase Items
            |--------------------------------------------------------------------------
            */

            'items' => ['required', 'array', 'min:1'],

            'items.*.medicine_id' => [
                'required',
                'exists:medicines,id'
            ],


            'items.*.batch_number' => [
                'nullable',
                'string',
                'max:100'
            ],

            'items.*.expiry_date' => [
                'nullable',
                'date'
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'items.*.free_quantity' => [
                'nullable',
                'integer',
                'min:0'
            ],

            'items.*.purchase_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'items.*.selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'items.*.discount' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'items.*.tax' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'items.*.total' => [
                'required',
                'numeric',
                'min:0'
            ],

        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [

            'supplier_id.required' => 'Please select a supplier.',

            'purchase_date.required' => 'Purchase date is required.',

            'items.required' => 'Please add at least one medicine.',

            'items.min' => 'At least one medicine is required.',

            'items.*.medicine_id.required' => 'Please select a medicine.',

            'items.*.quantity.required' => 'Quantity is required.',

            'items.*.purchase_price.required' => 'Purchase price is required.',

            'items.*.selling_price.required' => 'Selling price is required.',

        ];
    }
}
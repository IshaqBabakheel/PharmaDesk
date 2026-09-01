<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('suppliers.create');
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'company_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'contact_person' => [
                'nullable',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'alternate_phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:suppliers,email',
            ],

            'website' => [
                'nullable',
                'url',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'ntn' => [
                'nullable',
                'string',
                'max:50',
            ],

            'strn' => [
                'nullable',
                'string',
                'max:50',
            ],

            'opening_balance' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'balance_type' => [
                'required',
                'in:Payable,Receivable',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => 'Supplier name is required.',

            'phone.required' => 'Phone number is required.',

            'email.unique' => 'This email already exists.',

            'balance_type.required' => 'Please select balance type.',

        ];
    }
}

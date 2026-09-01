<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreManufacturerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:manufacturers,name',

            'contact_person' => 'nullable|string|max:255',

            'phone' => 'nullable|string|max:30',

            'email' => 'nullable|email|max:255',

            'website' => 'nullable|url|max:255',

            'address' => 'nullable|string',

            'city' => 'nullable|string|max:100',

            'country' => 'nullable|string|max:100',

            'notes' => 'nullable|string',

            'status' => 'required|boolean',

            'sort_order' => 'nullable|integer|min:0',

        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Manufacturer name is required.',
            'name.unique' => 'This Manufacturer already exists.',
            'name.max' => 'Manufacturer name may not exceed 100 characters.',

            'description.max' => 'Description may not exceed 1000 characters.',

            'status.required' => 'Please select the Manufacturer status.',

            'sort_order.integer' => 'Sort order must be a number.',
            'sort_order.min' => 'Sort order cannot be negative.',
        ];
    }

    /**
     * Friendly attribute names.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Manufacturer name',
            'sort_order' => 'sort order',
        ];
    }
}

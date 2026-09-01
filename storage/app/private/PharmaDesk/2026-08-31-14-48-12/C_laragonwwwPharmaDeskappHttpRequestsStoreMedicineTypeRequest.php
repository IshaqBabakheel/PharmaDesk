<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:100',
                'unique:medicine_types,name',
            ],

            'description' => [
                'nullable',
                'string',
                'max:1000',
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

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Medicine type name is required.',

            'name.unique' => 'This medicine type already exists.',

            'name.max' => 'Medicine type cannot exceed 100 characters.',

            'description.max' => 'Description cannot exceed 1000 characters.',

            'sort_order.integer' => 'Sort order must be a number.',

            'sort_order.min' => 'Sort order cannot be negative.',

        ];
    }

    /**
     * Friendly Names
     */
    public function attributes(): array
    {
        return [

            'name' => 'Medicine Type',

            'sort_order' => 'Sort Order',

        ];
    }
}

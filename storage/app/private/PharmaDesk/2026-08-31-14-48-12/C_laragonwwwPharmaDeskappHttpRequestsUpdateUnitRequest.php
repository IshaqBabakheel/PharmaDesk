<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
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

            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('units')->ignore($this->unit),
            ],

            'short_name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('units')->ignore($this->unit, 'id'),
            ],

            'description' => 'nullable|string|max:1000',

            'status' => 'required|boolean',

            'sort_order' => 'nullable|integer|min:0',

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Unit name is required.',

            'name.unique' => 'This Unit already exists.',

            'name.max' => 'Unit cannot exceed 100 characters.',

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

            'name' => 'Medicine Unit',

            'sort_order' => 'Sort Order',

        ];
    }
}

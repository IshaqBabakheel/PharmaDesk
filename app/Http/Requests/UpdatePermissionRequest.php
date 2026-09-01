<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('permissions.edit');
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'module' => [
                'required',
                'string',
                'max:50',
            ],

            'action' => [
                'required',
                'string',
                'max:50',
            ],

        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [

            'module.required' => 'Module name is required.',

            'action.required' => 'Please select an action.',

        ];
    }
}
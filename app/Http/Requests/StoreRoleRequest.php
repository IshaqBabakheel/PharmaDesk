<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('roles.create');
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
                'max:100',
                'unique:roles,name',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'exists:permissions,name',
            ],

        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Role name is required.',

            'name.unique' => 'This role already exists.',

            'permissions.*.exists' => 'Invalid permission selected.',

        ];
    }
}
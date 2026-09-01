<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('roles.edit');
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

                Rule::unique('roles', 'name')
                    ->ignore($this->role),

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
     * Validation messages.
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
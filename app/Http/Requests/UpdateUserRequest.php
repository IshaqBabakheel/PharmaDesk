<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('users.edit');
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

            'email' => [

                'required',

                'email',

                Rule::unique('users')
                    ->ignore($this->user),

            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'gender' => [
                'nullable',
                'in:Male,Female,Other',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'required',
                'boolean',
            ],

            'password' => [
                'nullable',
                'confirmed',
                'min:8',
            ],

            'roles' => [
                'required',
                'array',
            ],

            'roles.*' => [
                'exists:roles,name',
            ],

        ];
    }

    /**
     * Validation messages.
     */
    public function messages(): array
    {
        return [

            'roles.required' => 'Please assign at least one role.',

            'email.unique' => 'This email address is already taken.',

        ];
    }
}
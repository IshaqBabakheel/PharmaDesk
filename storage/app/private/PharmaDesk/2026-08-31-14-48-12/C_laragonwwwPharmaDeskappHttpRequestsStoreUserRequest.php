<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('users.create');
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
                'max:255',
                'unique:users,email',
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
                'required',
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

            'name.required' => 'Please enter the full name.',

            'email.required' => 'Email address is required.',

            'email.unique' => 'This email address already exists.',

            'password.required' => 'Password is required.',

            'password.confirmed' => 'Password confirmation does not match.',

            'roles.required' => 'Please assign at least one role.',

        ];
    }
}
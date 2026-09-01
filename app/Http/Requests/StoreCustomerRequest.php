<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;



class StoreCustomerRequest extends FormRequest
{


    public function authorize(): bool
    {

        return true;

    }




    public function rules(): array
    {

        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */


            'name' => [

                'required',

                'string',

                'max:255'

            ],




            'phone' => [

                'nullable',

                'string',

                'max:20',

                'unique:customers,phone'

            ],




            'email' => [

                'nullable',

                'email',

                'max:255',

                'unique:customers,email'

            ],




            'gender' => [

                'nullable',

                'in:male,female,other'

            ],




            'date_of_birth' => [

                'nullable',

                'date'

            ],






            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */


            'address' => [

                'nullable',

                'string'

            ],




            'city' => [

                'nullable',

                'string',

                'max:100'

            ],




            'state' => [

                'nullable',

                'string',

                'max:100'

            ],




            'country' => [

                'nullable',

                'string',

                'max:100'

            ],






            /*
            |--------------------------------------------------------------------------
            | Customer Type
            |--------------------------------------------------------------------------
            */


            'customer_type' => [

                'required',

                'in:regular,walk_in,corporate'

            ],






            /*
            |--------------------------------------------------------------------------
            | Credit Management
            |--------------------------------------------------------------------------
            */


            'credit_limit' => [

                'nullable',

                'numeric',

                'min:0'

            ],




            'opening_balance' => [

                'nullable',

                'numeric',

                'min:0'

            ],




            'balance_type' => [

                'required',

                'in:debit,credit'

            ],






            /*
            |--------------------------------------------------------------------------
            | Medical Information
            |--------------------------------------------------------------------------
            */


            'blood_group' => [

                'nullable',

                'string',

                'max:10'

            ],




            'allergies' => [

                'nullable',

                'string'

            ],




            'notes' => [

                'nullable',

                'string'

            ],


        ];

    }





    public function messages(): array
    {

        return [
            'name.required' => 'Customer name is required.',

            'phone.unique' => 'This phone number already exists.',

            'email.unique' => 'This email already exists.',

            'customer_type.required' => 'Customer type is required.',
        ];

    }


}
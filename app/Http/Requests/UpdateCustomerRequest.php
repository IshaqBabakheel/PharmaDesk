<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;



class UpdateCustomerRequest extends FormRequest
{


    public function authorize(): bool
    {

        return true;

    }




    public function rules(): array
    {


        $customerId =
            $this->route('customer')->id;



        return [



            'name' => [

                'required',

                'string',

                'max:255'

            ],




            'phone' => [

                'nullable',

                'string',

                'max:20',

                'unique:customers,phone,'
                .$customerId

            ],




            'email' => [

                'nullable',

                'email',

                'max:255',

                'unique:customers,email,'
                .$customerId

            ],





            'gender' => [

                'nullable',

                'in:male,female,other'

            ],





            'date_of_birth' => [

                'nullable',

                'date'

            ],






            'address' => [

                'nullable',

                'string'

            ],




            'city' => [

                'nullable',

                'string'

            ],




            'state' => [

                'nullable',

                'string'

            ],




            'country' => [

                'nullable',

                'string'

            ],





            'customer_type' => [

                'required',

                'in:regular,walk_in,corporate'

            ],




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




            'blood_group' => [

                'nullable',

                'string'

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
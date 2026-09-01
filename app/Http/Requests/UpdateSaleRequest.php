<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;



class UpdateSaleRequest extends FormRequest
{


    public function authorize(): bool
    {

        return true;

    }



    public function rules(): array
    {

        return [


            'customer_id' => [

                'nullable',

                'exists:customers,id'

            ],



            'doctor_name' => [

                'nullable',

                'string',

                'max:255'

            ],



            'sale_date' => [

                'required',

                'date'

            ],



            'discount_type' => [

                'required',

                'in:fixed,percentage'

            ],



            'discount' => [

                'nullable',

                'numeric',

                'min:0'

            ],



            'tax_type' => [

                'required',

                'in:fixed,percentage'

            ],



            'tax' => [

                'nullable',

                'numeric',

                'min:0'

            ],



            'shipping' => [

                'nullable',

                'numeric',

                'min:0'

            ],



            'other_charges' => [

                'nullable',

                'numeric',

                'min:0'

            ],



            'paid_amount' => [

                'required',

                'numeric',

                'min:0'

            ],



            'status' => [

                'required',

                'in:draft,completed,cancelled'

            ],




            'items' => [

                'required',

                'array',

                'min:1'

            ],



            'items.*.medicine_id' => [

                'required',

                'exists:medicines,id'

            ],



            'items.*.batch_number' => [

                'nullable',

                'string'

            ],



            'items.*.quantity' => [

                'required',

                'integer',

                'min:1'

            ],



            'items.*.selling_price' => [

                'required',

                'numeric',

                'min:0.01'

            ],


        ];

    }

    public function messages(): array
    {

        return [


            'items.required' =>
                'Sale must contain at least one medicine.',



            'items.min' =>
                'Sale must contain at least one medicine.',



            'items.*.medicine_id.required' =>
                'Medicine selection is required.',



            'items.*.quantity.min' =>
                'Quantity must be greater than zero.',


        ];

    }

}

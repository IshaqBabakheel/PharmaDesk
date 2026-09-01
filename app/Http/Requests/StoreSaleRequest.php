<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class StoreSaleRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }


    // public function rules(): array
    // {
    //     return [

    //         'customer_id'=>['required','exists:customers,id'],

    //         'sale_date'=>['required','date'],

    //         'doctor_name'=>['nullable','string','max:255'],

    //         'discount_type'=>['required','in:Fixed,Percentage'],

    //         'discount'=>['nullable','numeric','min:0'],

    //         'tax_type'=>['required','in:Fixed,Percentage'],

    //         'tax'=>['nullable','numeric','min:0'],

    //         'shipping'=>['nullable','numeric','min:0'],

    //         'other_charges'=>['nullable','numeric','min:0'],

    //         'paid_amount'=>['nullable','numeric','min:0'],

    //         'status'=>['required','in:Draft,Completed,Cancelled'],

    //         'notes'=>['nullable','string'],

    //         'items'=>['required','array','min:1'],

    //         'items.*.medicine_id'=>['required','exists:medicines,id'],

    //         'items.*.batch_number'=>['required'],

    //         'items.*.expiry_date'=>['nullable','date'],

    //         'items.*.quantity'=>['required','numeric','min:1'],

    //         'items.*.free_quantity'=>['nullable','numeric','min:0'],

    //         'items.*.selling_price'=>['required','numeric','min:0'],

    //     ];
    // }

    public function rules(): array
    {


        return [


            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */


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




            /*
            |--------------------------------------------------------------------------
            | Amounts
            |--------------------------------------------------------------------------
            */


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




            /*
            |--------------------------------------------------------------------------
            | Payment
            |--------------------------------------------------------------------------
            */


            'paid_amount' => [

                'required',

                'numeric',

                'min:0'

            ],





            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */


            'status' => [

                'required',

                'in:draft,completed,cancelled'

            ],



            'notes' => [

                'nullable',

                'string'

            ],




            /*
            |--------------------------------------------------------------------------
            | Sale Items
            |--------------------------------------------------------------------------
            */


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



            'items.*.expiry_date' => [

                'nullable',

                'date'

            ],




            'items.*.quantity' => [

                'required',

                'integer',

                'min:1'

            ],



            'items.*.free_quantity' => [

                'nullable',

                'integer',

                'min:0'

            ],



            'items.*.purchase_price' => [

                'required',

                'numeric',

                'min:0'

            ],



            'items.*.selling_price' => [

                'required',

                'numeric',

                'min:0.01'

            ],



            'items.*.discount' => [

                'nullable',

                'numeric',

                'min:0'

            ],



            'items.*.tax' => [

                'nullable',

                'numeric',

                'min:0'

            ],



            'items.*.total' => [

                'required',

                'numeric',

                'min:0'

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



    

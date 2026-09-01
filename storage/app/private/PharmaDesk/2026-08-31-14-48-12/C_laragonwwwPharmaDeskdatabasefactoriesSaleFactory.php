<?php

namespace Database\Factories;


use App\Models\Customer;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;


class SaleFactory extends Factory
{

    public function definition(): array
    {

        $grandTotal = fake()->randomFloat(
            2,
            500,
            10000
        );


        $paidAmount = fake()->randomElement([

            $grandTotal,

            $grandTotal / 2,

            0

        ]);



        $dueAmount = max(
            $grandTotal - $paidAmount,
            0
        );



        return [

            'invoice_number' => 
                'SAL-' . fake()
                ->unique()
                ->numberBetween(
                    100000,
                    999999
                ),


            'customer_id' =>
                Customer::query()
                ->inRandomOrder()
                ->value('id'),



            'doctor_name' =>
                fake()->optional()
                ->name(),



            'sale_date' =>
                fake()->date(),



            'subtotal' =>
                $grandTotal,


            'discount_type' =>
                'fixed',



            'discount' =>
                0,



            'tax_type' =>
                'fixed',



            'tax' =>
                0,



            'shipping' =>
                0,



            'other_charges' =>
                0,



            'grand_total' =>
                $grandTotal,



            'paid_amount' =>
                $paidAmount,



            'due_amount' =>
                $dueAmount,



            'payment_status' =>
                match(true)
                {

                    $dueAmount == 0 =>
                        'paid',


                    $paidAmount > 0 =>
                        'partial',


                    default =>
                        'due'

                },



            'status' => fake()->randomElement([

                'completed',

                'completed',

                'draft'

            ]),



            'notes' => fake()->optional()->sentence(),

            'created_by' => User::query()->inRandomOrder()->value('id'),

            'updated_by' => null,

            'deleted_by' => null,   


        ];

    }

}
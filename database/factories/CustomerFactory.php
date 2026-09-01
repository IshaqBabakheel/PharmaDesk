<?php

namespace Database\Factories;


use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;



class CustomerFactory extends Factory
{


    public function definition(): array
    {


        $type = fake()->randomElement([

            'credit',

            'regular',

            'walk_in',

            'corporate'

        ]);



        $hasCredit = fake()->boolean(40);

        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => fake()->name(),

            'phone' => fake()->unique()->numerify('03#########'),

            'email' => fake()->unique()->safeEmail(),

            'gender' => fake()->randomElement([
                'male',
                'female'
            ]),



            'date_of_birth' => fake()->date('2000-01-01'),

            /*
            |--------------------------------------------------------------------------
            | Address
            |--------------------------------------------------------------------------
            */

            'address' =>fake()->streetAddress(),

            'city' =>fake()->randomElement([

                'Karachi',

                'Lahore',

                'Islamabad',

                'Peshawar',

                'Swat'

            ]),

            'state' =>'Pakistan',

            'country' =>'Pakistan',

            /*
            |--------------------------------------------------------------------------
            | Customer Type
            |--------------------------------------------------------------------------
            */

            'customer_type' =>$type,





            /*
            |--------------------------------------------------------------------------
            | Credit
            |--------------------------------------------------------------------------
            */


            'credit_limit' => $hasCredit ? fake()->randomFloat(2, 5000, 100000) : 0,

            'opening_balance' => $hasCredit ? fake()->randomFloat(2, 1000, 20000) : 0,

            'balance_type' => $hasCredit ? 'credit' : 'debit',


            /*
            |--------------------------------------------------------------------------
            | Medical
            |--------------------------------------------------------------------------
            */


            'blood_group' =>fake()->randomElement([

                'A+',

                'B+',

                'O+',

                'AB+',

                'O-'

            ]),


            'allergies' =>fake()->optional()->sentence(),

            'notes' =>fake()->optional()->sentence(),

            /*
            |--------------------------------------------------------------------------
            | Audit
            |--------------------------------------------------------------------------
            */

            'created_by' => User::query()->inRandomOrder()->value('id'),

            'updated_by' => null,

            'deleted_by' => null,
        ];

    }

}
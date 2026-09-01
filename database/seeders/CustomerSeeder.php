<?php

namespace Database\Seeders;


use App\Models\Customer;

use Illuminate\Database\Seeder;



class CustomerSeeder extends Seeder
{

    public function run(): void
    {
        Customer::factory()->count(100)->create();

        /*
        |--------------------------------------------------------------------------
        | Important Pharmacy Customers
        |--------------------------------------------------------------------------
        */
        Customer::create([
            'name' => 'Walk-in Customer',

            'phone' => '00000000000',

            'customer_type' => 'walk_in',

            'credit_limit' => 0,

            'opening_balance' => 0,

            'balance_type' => 'debit',

            'created_by' => 1,
        ]);





        Customer::create([
            'name' => 'Prime Care Hospital',

            'phone' => '03001234567',

            'email' => 'accounts@primecare.com',

            'customer_type' => 'corporate',

            'credit_limit' => 500000,

            'opening_balance' => 0,

            'balance_type' => 'credit',

            'created_by' => 1,
        ]);



    }


}
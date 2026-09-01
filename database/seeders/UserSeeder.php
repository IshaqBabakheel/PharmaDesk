<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Super Admin
        |--------------------------------------------------------------------------
        */

        $superAdmin = User::firstOrCreate(

            [
                'email' => 'superadmin@example.com'
            ],

            [

                'name' => 'Super Admin',

                'password' => Hash::make('password'),

                'phone' => '03000000000',

                'status' => true,

            ]

        );

        $superAdmin->assignRole('Super Admin');



        /*
        |--------------------------------------------------------------------------
        | Administrator
        |--------------------------------------------------------------------------
        */

        $admin = User::firstOrCreate(

            [
                'email' => 'admin@example.com'
            ],

            [

                'name' => 'Administrator',

                'password' => Hash::make('password'),

                'phone' => '03111111111',

                'status' => true,

            ]

        );

        $admin->assignRole('Admin');



        /*
        |--------------------------------------------------------------------------
        | Manager
        |--------------------------------------------------------------------------
        */

        $manager = User::firstOrCreate(

            [
                'email' => 'manager@example.com'
            ],

            [

                'name' => 'Manager',

                'password' => Hash::make('password'),

                'phone' => '03222222222',

                'status' => true,

            ]

        );

        $manager->assignRole('Manager');



        /*
        |--------------------------------------------------------------------------
        | Pharmacist
        |--------------------------------------------------------------------------
        */

        $pharmacist = User::firstOrCreate(

            [
                'email' => 'pharmacist@example.com'
            ],

            [

                'name' => 'Pharmacist',

                'password' => Hash::make('password'),

                'phone' => '03333333333',

                'status' => true,

            ]

        );

        $pharmacist->assignRole('Pharmacist');



        /*
        |--------------------------------------------------------------------------
        | Cashier
        |--------------------------------------------------------------------------
        */

        $cashier = User::firstOrCreate(

            [
                'email' => 'cashier@example.com'
            ],

            [

                'name' => 'Cashier',

                'password' => Hash::make('password'),

                'phone' => '03444444444',

                'status' => true,

            ]

        );

        $cashier->assignRole('Cashier');

    }
}



        
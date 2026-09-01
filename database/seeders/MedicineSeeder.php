<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        /*
        |--------------------------------------------------------------------------
        | Create Demo Medicines
        |--------------------------------------------------------------------------
        */

        Medicine::factory()

            ->count(50)

            ->create();

    }
}
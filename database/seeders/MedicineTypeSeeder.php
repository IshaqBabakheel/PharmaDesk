<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\MedicineType;

class MedicineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [

            [
                'name' => 'Prescription',
                'description' => 'Medicines that require a doctor prescription.',
            ],

            [
                'name' => 'OTC',
                'description' => 'Over-the-counter medicines available without prescription.',
            ],

            [
                'name' => 'Controlled',
                'description' => 'Medicines controlled by government regulations.',
            ],

            [
                'name' => 'Herbal',
                'description' => 'Medicines made from herbal ingredients.',
            ],

            [
                'name' => 'Supplement',
                'description' => 'Vitamins, minerals and dietary supplements.',
            ],

        ];

        foreach ($types as $index => $type) {

            MedicineType::create([

                'name' => $type['name'],

                'slug' => Str::slug($type['name']),

                'description' => $type['description'],

                'status' => true,

                'sort_order' => $index + 1,

            ]);

        }
    }
}

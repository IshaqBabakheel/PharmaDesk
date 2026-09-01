<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Manufacturer;
use Illuminate\Support\Str;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Manufacturer::factory()

        // ->count(50)

        // ->create();
        
        $manufacturers = [

            [
                'name' => 'Getz Pharma',
                'contact_person' => 'Muhammad Ali',
                'phone' => '+92 21 111 111 111',
                'email' => 'info@getzpharma.com',
                'website' => 'https://www.getzpharma.com',
                'city' => 'Karachi',
                'country' => 'Pakistan',
            ],

            [
                'name' => 'GSK Pakistan',
                'contact_person' => 'Ahmed Khan',
                'phone' => '+92 21 222 222 222',
                'email' => 'info@gsk.com.pk',
                'website' => 'https://pk.gsk.com',
                'city' => 'Karachi',
                'country' => 'Pakistan',
            ],

            [
                'name' => 'Abbott Laboratories',
                'contact_person' => 'Usman Tariq',
                'phone' => '+92 42 333 333 333',
                'email' => 'info@abbott.com',
                'website' => 'https://www.abbott.com',
                'city' => 'Lahore',
                'country' => 'Pakistan',
            ],

        ];

        foreach ($manufacturers as $index => $manufacturer) {

            Manufacturer::create([

                ...$manufacturer,

                'status' => true,

                'sort_order' => $index + 1,

                'created_by' => 1,

            ]);

        }

    }
}

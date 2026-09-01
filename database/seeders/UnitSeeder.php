<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Unit;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units=[

            ['Tablet','Tab'],

            ['Capsule','Cap'],

            ['Bottle','Btl'],

            ['Strip','Strip'],

            ['Box','Box'],

            ['Tube','Tube'],

            ['Ampoule','Amp'],

            ['Vial','Vial'],

            ['Sachet','Sach'],

            ['Piece','Pcs'],

            ['Milliliter','ml'],

            ['Liter','L'],

            ['Gram','g'],

            ['Kilogram','kg'],

        ];

        foreach($units as $unit){

            Unit::create([

                'name'=>$unit[0],

                'short_name'=>$unit[1],

                'status'=>1,

            ]);

        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\MedicineCategory;


class MedicineCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [

            'Tablet',

            'Capsule',

            'Syrup',

            'Injection',

            'Cream',

            'Ointment',

            'Gel',

            'Drops',

            'Powder',

            'Inhaler',

            'Suppository',

            'Patch',

            'Lotion',

            'Spray'

        ];

        foreach ($categories as $index=>$category){

            MedicineCategory::create([

                'name'=>$category,

                'slug'=>Str::slug($category),

                'status'=>true,

                'sort_order'=>$index+1

            ]);

        }
    }
}

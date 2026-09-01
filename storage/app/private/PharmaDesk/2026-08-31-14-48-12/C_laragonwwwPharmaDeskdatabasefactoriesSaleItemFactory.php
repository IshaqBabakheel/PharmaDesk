<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleItemFactory extends Factory
{
    public function definition(): array
    {
        return [

            'sale_id'=>Sale::factory(),

            'medicine_id'=>Medicine::factory(),

            'batch_number'=>'B'.fake()->numerify('#####'),

            'expiry_date'=>now()->addYear(),

            'quantity'=>5,

            'free_quantity'=>0,

            'purchase_price'=>150,

            'selling_price'=>200,

            'discount'=>0,

            'tax'=>0,

            'total'=>1000,

        ];
    }
}
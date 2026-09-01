<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Unit;
use App\Models\Manufacturer;
use App\Models\MedicineCategory;
use App\Models\MedicineType;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $purchase = fake()->randomFloat(2, 20, 1000);

        $selling = $purchase + fake()->randomFloat(2, 10, 300);

        $openingStock = fake()->numberBetween(10, 500);

        return [

            /*
            |--------------------------------------------------------------------------
            | Basic Information
            |--------------------------------------------------------------------------
            */

            'name' => fake()->unique()->words(2, true),

            'generic_name' => fake()->optional()->word(),

            'sku' => strtoupper(fake()->bothify('MED-######')),

            'barcode' => fake()->unique()->ean13(),

            'medicine_code' => strtoupper(fake()->bothify('MD######')),

            /*
            |--------------------------------------------------------------------------
            | Relationships
            |--------------------------------------------------------------------------
            */

            'medicine_category_id' => MedicineCategory::inRandomOrder()->value('id'),

            'medicine_type_id' => MedicineType::inRandomOrder()->value('id'),

            'manufacturer_id' => Manufacturer::inRandomOrder()->value('id'),

            'unit_id' => Unit::inRandomOrder()->value('id'),

            /*
            |--------------------------------------------------------------------------
            | Pricing
            |--------------------------------------------------------------------------
            */

            'purchase_price' => $purchase,

            'selling_price' => $selling,

            'wholesale_price' => $purchase + fake()->randomFloat(2, 5, 150),

            /*
            |--------------------------------------------------------------------------
            | Stock
            |--------------------------------------------------------------------------
            */

            'opening_stock' => $openingStock,

            'current_stock' => $openingStock,

            'minimum_stock' => fake()->numberBetween(5, 20),

            'maximum_stock' => fake()->numberBetween(200, 1000),

            'reorder_level' => fake()->numberBetween(10, 50),

            /*
            |--------------------------------------------------------------------------
            | Other
            |--------------------------------------------------------------------------
            */

            'has_expiry' => fake()->boolean(90),

            'shelf_life_months' => fake()->randomElement([12,18,24,36]),

            'tax_percentage' => fake()->randomElement([0,5,10,15,18]),

            'description' => fake()->sentence(),

            'status' => true,

            'sort_order' => fake()->numberBetween(1,100),

            'created_by' => User::query()->value('id'),

        ];
    }
}
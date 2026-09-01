<?php

namespace Database\Factories;

use App\Models\Medicine;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseItem>
 */
class PurchaseItemFactory extends Factory
{
    protected $model = PurchaseItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 100);

        $price = fake()->randomFloat(2, 5, 500);

        return [

            'purchase_id' => Purchase::factory(),

            'medicine_id' => Medicine::factory(),

            'batch_number' => strtoupper(fake()->bothify('BT###??')),

            'expiry_date' => fake()->dateTimeBetween('+6 months', '+4 years'),

            'quantity' => $qty,

            'free_quantity' => fake()->numberBetween(0, 20),

            'purchase_price' => $price,

            'selling_price' => $price + fake()->randomFloat(2, 2, 150),

            'discount' => fake()->randomFloat(2, 0, 100),

            'tax' => fake()->randomFloat(2, 0, 50),

            'total' => $qty * $price,

        ];
    }
}
<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $counter = 1;
        return [
            'supplier_code' => 'SUP-' . str_pad($counter++, 5, '0', STR_PAD_LEFT),
            'name' => fake()->company(),
            'company_name' => fake()->company(),
            'contact_person' => fake()->name(),
            'phone' => fake()->phoneNumber(),
            'alternate_phone' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'website' => fake()->url(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'country' => 'Pakistan',
            'postal_code' => fake()->postcode(),
            'ntn' => fake()->numerify('######-#'),
            'strn' => fake()->numerify('###########'),
            'opening_balance' => fake()->randomFloat(2, 0, 50000),
            'balance_type' => fake()->randomElement([
                'Payable',
                'Receivable'
            ]),
            'notes' => fake()->sentence(),
            'status' => true,
            'sort_order' => 0,
        ];
    }
}

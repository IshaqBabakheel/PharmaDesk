<?php

namespace Database\Factories;

use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Manufacturer>
 */
class ManufacturerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => fake()->unique()->company(),

            'contact_person' => fake()->name(),

            'phone' => fake()->phoneNumber(),

            'email' => fake()->unique()->safeEmail(),

            'website' => fake()->url(),

            'address' => fake()->address(),

            'city' => fake()->city(),

            'country' => fake()->country(),

            'notes' => fake()->sentence(),

            'status' => fake()->boolean(90),

            'sort_order' => fake()->numberBetween(1,100),

            'created_by' => User::inRandomOrder()->value('id'),

        ];
    }
}


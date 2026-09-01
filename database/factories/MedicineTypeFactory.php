<?php

namespace Database\Factories;

use App\Models\MedicineType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MedicineType>
 */
class MedicineTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [

            'name' => ucfirst($name),

            'slug' => Str::slug($name),

            'description' => fake()->sentence(),

            'status' => true,

            'sort_order' => 0,

        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name'=>$this->faker->unique()->randomElement([

                'Tablet',

                'Capsule',

                'Bottle',

                'Strip',

                'Box',

                'Tube',

                'Ampoule',

                'Vial',

                'Sachet',

                'Piece',

            ]),

            'short_name'=>$this->faker->lexify('???'),

            'description'=>$this->faker->sentence(),

            'status'=>true,

            'sort_order'=>0,

        ];
    }
}


<?php

namespace Database\Factories;

use App\Models\Crafter;
use App\Models\Customization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $crafter = Crafter::inRandomOrder()->first();
        $customization = Customization::inRandomOrder()->first();


        return [
            'crafter_id' => $crafter->id,
            'customization_id' => $customization->id,
            'name' => fake()->word(),
            'description' => fake()->text(200),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => rand(0,7),
            'price' => fake()->randomFloat(2,10,999999),
            'date' => fake()->DateTime(),
            'delivery_address' => fake()->address(),
            'facturation_address' => fake()->address(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Tax;
use App\Models\User;
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
        $userId = User::inRandomOrder()->first()->id;

        return [
            'user_id' => $userId,
            'status' => rand(0,7),
            'price' => fake()->randomFloat(2,10,999999),
            'date' => fake()->DateTime(),
            'delivery_address' => fake()->address(),
            'facturation_address' => fake()->address(),
        ];
    }
}

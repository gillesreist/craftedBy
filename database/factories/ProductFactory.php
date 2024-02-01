<?php

namespace Database\Factories;

use App\Models\Customization;
use App\Models\User;
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

        $user = User::inRandomOrder()->first();
        $customization = Customization::inRandomOrder()->first();


        return [
            'user_id' => $user->id,
            // 'customization_id' => $customization->id,
            'name' => fake()->word(),
            'description' => fake()->text(200),
        ];
    }
}

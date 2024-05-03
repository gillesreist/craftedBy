<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\Sku;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sku>
 */
class SkuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'unit_price' => fake()->randomFloat(2,10,999999),
            'status' => rand(0,2),
            'is_active' => rand(0,1),
        ];
    }
}

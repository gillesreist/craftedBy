<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->LastName(),
            'type' => rand(0,2),
            'name' => fake()->word(),
            'first_address_line' => fake()->streetAddress(),
            'second_address_line' => fake()->secondaryAddress(),
            'postal_code' => fake()->postcode(),
            'city' => fake()->city(),
        ];
    }
}

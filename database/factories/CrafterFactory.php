<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Crafter>
 */
class CrafterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $userWithoutCrafter = User::doesntHave('crafter')->inRandomOrder()->first();

        return [
            'user_id' => $userWithoutCrafter->id,
            'information' => fake()->text(200),
            'story' => fake()->text(200),
            'crafting_process' => fake()->text(200),
            'location' => fake()->text(200),
            'material_preferences' => fake()->text(200),        ];
    }
}

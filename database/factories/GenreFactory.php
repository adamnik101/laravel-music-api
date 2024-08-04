<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => ucfirst(fake()->word()),
            "cover" => $this->faker->imageUrl(500, 500, null, true),
            "hex_color" => fake()->hexColor()
        ];
    }
}

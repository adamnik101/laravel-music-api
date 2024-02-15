<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
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
            "name" => $name,
            "release_year" => fake()->year(),
            "cover" => fake()->imageUrl(500, 500, null, false, $name),
            "artist_id" => Artist::inRandomOrder()->first()->id
        ];
    }
}

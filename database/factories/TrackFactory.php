<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $owner = Artist::inRandomOrder()->first();
        $album = Album::where("artist_id", $owner->id)->inRandomOrder()->first();
        $genre = Genre::inRandomOrder()->first();
        $title = ucfirst(fake()->word());
        return [
            "title" => $title,
            "path" => fake()->url(),
            "explicit" => fake()->boolean(),
            "owner_id" => $owner->id,
            "album_id" => $album ? $album->id : null,
            "genre_id" => $genre->id,
            "cover" => fake()->imageUrl(500, 500, null, false, $title),
            "duration" => fake()->numberBetween(0, 1500)
        ];
    }
}

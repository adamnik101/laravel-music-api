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
        $owner = Artist::query()->inRandomOrder()->first();
        $album = Album::query()->where("artist_id", $owner->id)->inRandomOrder()->first();
        $genre = Genre::query()->inRandomOrder()->first();
        $title = ucfirst(fake()->word());
        return [
            "title" => $title,
            "path" => 'https://files.freemusicarchive.org/storage-freemusicarchive-org/tracks/NUw5dxZMgtizGOgK2GsRpnumKqvehxxbJo1PTujp.mp3', //fake path
            "explicit" => fake()->boolean(),
            "owner_id" => $owner->id,
            "album_id" => $album?->id,
            "genre_id" => $genre->id,
            "cover" => fake()->imageUrl(500, 500, null, false, $title),
            "duration" => fake()->numberBetween(45, 360),
            "created_at" => fake()->dateTimeBetween('-1 years')
        ];
    }
}

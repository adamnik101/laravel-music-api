<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Playlist>
 */
class PlaylistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);
        $user = User::inRandomOrder()->first();
        return [
            "title" => $title,
            "description" => fake()->text(),
            "image_url" => fake()->imageUrl(500, 500, null, false, $title),
            "user_id" => $user->id
        ];
    }
}

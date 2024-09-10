<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Setting;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfUsers = 300;
        for ($i = 0; $i < $numberOfUsers; $i++) {
            $user = User::factory()->create();

            $user->settings()->save(Setting::factory()->makeOne());

            $numberOfLikes = rand(0,100);
            $tracks = null;
            if($numberOfLikes > 0) {
                $tracks = Track::query()->inRandomOrder()->take($numberOfLikes)->get();
                $user->likedTracks()->saveMany($tracks);
            }

            $numberOfAlbums = rand(0,30);
            $albums = null;
            if($numberOfAlbums > 0) {
                $albums = Album::query()->inRandomOrder()->take($numberOfAlbums)->get();
                $user->likedAlbums()->syncWithPivotValues($albums, [
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
            }

            $numberOfFollowings = rand(0, 50);
            $followings = null;
            if($numberOfFollowings > 0) {
                $followings = Artist::query()->inRandomOrder()->take($numberOfFollowings)->get();
                $user->followings()->syncWithPivotValues($followings, [
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
            }

            $numberOfPlayedTracks = rand(0, 100);
            $tracksPlayed = null;
            if ($numberOfPlayedTracks > 0) {
                $tracksPlayed = Track::query()->inRandomOrder()->take($numberOfPlayedTracks)->get();
                $user->trackPlays()->createMany($tracksPlayed->map(function ($track) {
                    return [
                        // If you need any additional attributes in the pivot table, you can add them here
                        'track_id' => $track->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                })->toArray());
            }
        }

    }
}

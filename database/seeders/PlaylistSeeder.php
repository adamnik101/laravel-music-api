<?php

namespace Database\Seeders;

use App\Models\Playlist;
use App\Models\Track;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PlaylistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Playlist::factory()->count(100)->create()
            ->each(function (Playlist $playlist) {
                $numberOfTracks = rand(0,100);
                if($numberOfTracks > 0) {
                    $tracks = Track::query()->inRandomOrder()->take($numberOfTracks)->get();

                    $playlist->tracks()->syncWithPivotValues($tracks, [
                        "created_at" => now(),
                        "updated_at" => now()
                    ]);
                }
        });
    }
}

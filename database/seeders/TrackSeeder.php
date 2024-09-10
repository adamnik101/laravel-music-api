<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Track;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $numberOfTracks = 10000;

        for ($i = 0; $i < $numberOfTracks; $i++) {
            $track = Track::factory()->create();

            $numberOfFeatures = rand(0, 4);
            $artists = null;

            if($numberOfFeatures > 0) {
                $artists = Artist::query()->inRandomOrder()->take($numberOfFeatures)->get();
                $track->features()->syncWithPivotValues($artists, [
                    "created_at" => now(),
                    "updated_at" => now()
                ]);
            }
        }
    }
}

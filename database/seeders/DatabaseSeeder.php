<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(GenreSeeder::class);
        $this->call(ArtistSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AlbumSeeder::class);
        $this->call(TrackSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PlaylistSeeder::class);

    }
}

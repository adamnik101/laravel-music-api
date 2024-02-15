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
        $this->call([
            GenreSeeder::class,
            ArtistSeeder::class,
            RoleSeeder::class,
            AlbumSeeder::class,
            TrackSeeder::class,
            UserSeeder::class,
            PlaylistSeeder::class
        ]);
    }
}

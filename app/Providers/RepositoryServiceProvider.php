<?php

namespace App\Providers;

use App\Repositories\Implementations\AdminRepository;
use App\Repositories\Implementations\AlbumRepository;
use App\Repositories\Implementations\ArtistRepository;
use App\Repositories\Implementations\AuthRepository;
use App\Repositories\Implementations\GenreRepository;
use App\Repositories\Implementations\PlaylistRepository;
use App\Repositories\Implementations\SearchRepository;
use App\Repositories\Implementations\TrackRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Interfaces\AdminInterface;
use App\Repositories\Interfaces\AlbumInterface;
use App\Repositories\Interfaces\ArtistInterface;
use App\Repositories\Interfaces\AuthInterface;
use App\Repositories\Interfaces\GenreInterface;
use App\Repositories\Interfaces\PlaylistInterface;
use App\Repositories\Interfaces\SearchInterface;
use App\Repositories\Interfaces\TrackInterface;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(AlbumInterface::class, AlbumRepository::class);
        $this->app->bind(TrackInterface::class, TrackRepository::class);
        $this->app->bind(GenreInterface::class, GenreRepository::class);
        $this->app->bind(PlaylistInterface::class, PlaylistRepository::class);
        $this->app->bind(ArtistInterface::class, ArtistRepository::class);
        $this->app->bind(AuthInterface::class, AuthRepository::class);
        $this->app->bind(SearchInterface::class, SearchRepository::class);
        $this->app->bind(AdminInterface::class, AdminRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

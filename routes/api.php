<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TrackController;
use \App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('/auth')->group(function () {
   Route::post('/login', [AuthController::class, 'login']);
   Route::post('/register', [AuthController::class, 'register']);
   Route::middleware(['auth:sanctum'])->group(function () {
      Route::get('/token', [AuthController::class, 'getToken']);
      Route::delete('/token', [AuthController::class, 'logout']);
      Route::get('me', [AuthController::class, 'getUser']);
      Route::get('user-role', [AuthController::class, 'fetchUserRole']);
   });
});
Route::get('/search', [SearchController::class, 'search']);

Route::whereUuid('id')->group(function () {
    Route::prefix('/me')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/tracks', [UserController::class, 'fetchUserLikedTracks']);
        Route::get('/albums', [UserController::class, 'fetchUserLikedAlbums']);
        Route::get('/artists', [UserController::class, 'fetchUserLikedArtists']);
        Route::get('/recently-played', [UserController::class, 'fetchRecentlyPlayedTracks']);

        Route::post('/tracks', [UserController::class, 'saveTrack']);
        Route::post('/albums', [UserController::class, 'saveAlbum']);
        Route::post('/artists', [UserController::class, 'saveArtist']);

        Route::delete('/tracks/{id}', [UserController::class, 'unsaveTrack']);
        Route::delete('/albums/{id}', [UserController::class, 'unsaveAlbum']);
        Route::delete('/artists/{id}', [UserController::class, 'unsaveArtist']);

        Route::post('/settings', [UserController::class, 'updateSettings']);
        Route::patch('/username', [UserController::class, 'updateUsername']);
        Route::post('/cover', [UserController::class, 'updateCover']);
    });

    Route::prefix('/users')->middleware(['auth:sanctum'])->group(function () {
        Route::get('/', [UserController::class, 'fetchAll']);
        Route::get('/{id}', [UserController::class, 'fetchOne']);
        Route::patch('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'delete']);

        Route::get('/search', [SearchController::class, 'searchUsers']);
    });
    Route::prefix('/roles')->middleware(['auth:sanctum'])->group(function () {
       Route::get('/', [RoleController::class, 'fetchAll']);
    });

    Route::prefix('/albums')->group(function () {
        Route::get('/', [AlbumController::class, 'fetchAll']);
        Route::get('/{id}', [AlbumController::class, 'fetchOne']);
        Route::get('/new-releases', [AlbumController::class, 'newReleases']);
        Route::get('/trending', [AlbumController::class, 'trending']);
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/search', [SearchController::class, 'searchAlbums']);

            Route::post('', [AlbumController::class, 'insert']);
            Route::patch('/{id}', [AlbumController::class, 'update']);
            Route::delete('/{id}', [AlbumController::class, 'delete']);
        });
    });

    Route::prefix('/artists')->group(function () {
        Route::get('/', [ArtistController::class, 'fetchAll']);
        Route::get('/{id}', [ArtistController::class, 'fetchOne']);
        Route::get('/trending', [ArtistController::class, 'trending']);
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/search', [SearchController::class, 'searchArtists']);

            Route::post('/', [ArtistController::class, 'insert']);
            Route::patch('/{id}', [ArtistController::class, 'update']);
            Route::delete('/{id}', [ArtistController::class, 'delete']);
            Route::post('/delete-many', [ArtistController::class, 'deleteMany']);
        });
    });

    Route::prefix('/tracks')->group(function () {
        Route::get('/', [TrackController::class, 'fetchAll']);
        Route::get('/{id}', [TrackController::class, 'fetchOne']);
        Route::get('/trending', [TrackController::class, 'trending']);
        Route::get('/new-releases', [TrackController::class, 'newReleases']);

        Route::middleware(['auth:sanctum'])->group(function () {
            Route::post('/', [TrackController::class, 'insert']);
            Route::get('/search', [SearchController::class, 'searchTracks']);
            Route::patch('/{id}', [TrackController::class, 'update']);
            Route::delete('/{id}', [TrackController::class, 'delete']);
            Route::post('/delete-many', [TrackController::class, 'deleteMany']);
        });
    });

    Route::prefix('/genres')->group(function () {
        Route::get('/', [GenreController::class, 'fetchAll']);
        Route::get('/{id}', [GenreController::class, 'fetchOne']);
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::patch('/{id}', [GenreController::class, 'update']);
            Route::delete('/{id}', [GenreController::class, 'delete']);
            Route::post('/', [GenreController::class, 'insert']);

        });
        Route::get('/search', [SearchController::class, 'searchGenres']);
    });

    Route::prefix('/playlists')->group(function () {
        Route::get('/{id}', [PlaylistController::class, 'fetchOne']);
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/', [PlaylistController::class, 'fetchAll']);
            Route::post('/', [PlaylistController::class, 'insert']);
            Route::patch('/{id}', [PlaylistController::class, 'update']);
            Route::delete('/{id}', [PlaylistController::class, 'delete']);

            Route::post('/tracks/{id}', [PlaylistController::class, 'insertTracks']);
            Route::delete('/{id}/tracks/{track}', [PlaylistController::class, 'removeTrack'])->where('track', '[0-9]*');
        });
    });

    Route::prefix('/admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/artists', [AdminController::class, 'artists']);
        Route::get('/albums', [AdminController::class, 'albums']);
        Route::get('/genres', [AdminController::class, 'genres']);
    });
});










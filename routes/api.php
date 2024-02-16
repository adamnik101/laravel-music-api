<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;
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
});

Route::whereUuid('id')->group(function () {
    Route::prefix('/users')->middleware(['auth:sanctum', 'ability:admin'])->group(function () {
        Route::get('/', [UserController::class, 'fetchAll']);
        Route::get('/{id}', [UserController::class, 'fetchOne']);
        Route::post('/{id}/update', [UserController::class, 'update']);
        Route::post('/{id}/delete', [UserController::class, 'delete']);
    });

    Route::prefix('/albums')->group(function () {
        Route::get('/', [AlbumController::class, 'fetchAll']);
        Route::get('/{id}', [AlbumController::class, 'fetchOne']);
        Route::post('/{id}/update', [AlbumController::class, 'update']);
        Route::post('/{id}/delete', [AlbumController::class, 'delete']);
    });

    Route::prefix('/artists')->group(function () {
        Route::get('/', [ArtistController::class, 'fetchAll']);
        Route::get('/{id}', [ArtistController::class, 'fetchOne']);
        Route::post('/{id}/update', [ArtistController::class, 'update']);
        Route::post('/{id}/delete', [ArtistController::class, 'delete']);
    });

    Route::prefix('/tracks')->group(function () {
        Route::get('/', [TrackController::class, 'fetchAll']);
        Route::get('/{id}', [TrackController::class, 'fetchOne']);
        Route::post('/{id}/update', [TrackController::class, 'update']);
        Route::post('/{id}/delete', [TrackController::class, 'delete']);

    });

    Route::prefix('/genres')->group(function () {
        Route::get('/', [GenreController::class, 'fetchAll']);
        Route::get('/{id}', [GenreController::class, 'fetchOne']);
        Route::post('/{id}/update', [GenreController::class, 'update']);
        Route::post('/{id}/delete', [GenreController::class, 'delete']);

    });

    Route::prefix('/playlists')->group(function () {
        Route::get('/', [PlaylistController::class, 'fetchAll']);
        Route::get('/{id}', [PlaylistController::class, 'fetchOne']);

        Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
            Route::post('/', [PlaylistController::class, 'insert']);
            Route::post('/{id}/update', [PlaylistController::class, 'update']);
            Route::post('/{id}/delete', [PlaylistController::class, 'delete']);
        });
    });
});










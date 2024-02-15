<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\TrackController;
use Illuminate\Http\Request;
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
Route::prefix('/users')->group(function () {
   Route::get('/', [UserController::class, 'fetchAll']);
   Route::get('/{id}', [UserController::class, 'fetchOne'])->whereUuid('id');
});

Route::prefix('/albums')->group(function () {
   Route::get('/', [AlbumController::class, 'fetchAll']);
   Route::get('/{id}', [AlbumController::class, 'fetchOne'])->whereUuid('id');
});

Route::prefix('/artists')->group(function () {
    Route::get('/', [ArtistController::class, 'fetchAll']);
    Route::get('/{id}', [ArtistController::class, 'fetchOne'])->whereUuid('id');
});

Route::prefix('/tracks')->group(function () {
   Route::get('/', [TrackController::class, 'fetchAll']);
   Route::get('/{id}', [TrackController::class, 'fetchOne'])->whereUuid('id');
});

Route::prefix('/genres')->group(function () {
    Route::get('/', [GenreController::class, 'fetchAll']);
    Route::get('/{id}', [GenreController::class, 'fetchOne'])->whereUuid('id');
});

Route::prefix('/playlists')->group(function () {
    Route::get('/', [PlaylistController::class, 'fetchAll']);
    Route::get('/{id}', [PlaylistController::class, 'fetchOne'])->whereUuid('id');
});


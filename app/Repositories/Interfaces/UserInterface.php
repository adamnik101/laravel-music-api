<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\UpdateSettingsRequest;
use Illuminate\Http\JsonResponse;

interface UserInterface extends BaseInterface
{
    function fetchUserLikedTracks() : JsonResponse;
    function fetchUserLikedAlbums() : JsonResponse;
    function fetchUserLikedArtists() : JsonResponse;
    function fetchRecentlyPlayedTracks() : JsonResponse;
    function saveTrack(string $track) : JsonResponse;
    function saveAlbum(string $album) : JsonResponse;
    function saveArtist(string $artist) : JsonResponse;
    function unsaveTrack(string $track) : JsonResponse;
    function unsaveAlbum(string $album) : JsonResponse;
    function unsaveArtist(string $artist) : JsonResponse;
    function updateSettings(array $data) : JsonResponse;
}

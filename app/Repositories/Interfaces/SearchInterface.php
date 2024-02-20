<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

interface SearchInterface
{
    function search(string $query) : JsonResponse;
    function searchTracks(array $query) : JsonResponse;
    function searchGenres(array $query) : JsonResponse;
    function searchArtists(array $query) : JsonResponse;
    function searchAlbums(array $query) : JsonResponse;
}

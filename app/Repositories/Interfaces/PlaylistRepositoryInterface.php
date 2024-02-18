<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\InsertTracksToPlaylistRequest;
use App\Http\Requests\PlaylistRequest;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Validator;

interface PlaylistRepositoryInterface extends BaseRepositoryInterface
{
    function insertTracks(array $trackIds, string $id, ?bool $confirm) : JsonResponse;
    function removeTrackFromPlaylist(string $playlist, string $track);
}

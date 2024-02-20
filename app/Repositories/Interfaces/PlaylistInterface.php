<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\InsertTracksToPlaylistRequest;
use App\Http\Requests\PlaylistRequest;
use App\Repositories\Interfaces\BaseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Validator;

interface PlaylistInterface extends BaseInterface
{
    function insertTracks(array $trackIds, string $id, ?bool $confirm) : JsonResponse;
    function removeTrack(string $playlist, string $track);
}

<?php

namespace App\Repositories\Implementations;

use App\Models\Playlist;
use App\Repositories\Interfaces\PlaylistRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;

class PlaylistRepository implements PlaylistRepositoryInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        $playlists = Playlist::query()->paginate(10);

        return $this->success("All playlists with pagination", $playlists);
    }

    function fetchOne(string $id): JsonResponse
    {
        $playlist = Playlist::query()->with('tracks')->find($id);

        if (!$playlist) return $this->error("No playlist found.", 404);

        return $this->success("Playlist detail", $playlist);
    }

    function delete(): JsonResponse
    {
        // TODO: Implement delete() method.
    }

    function update(): JsonResponse
    {
        // TODO: Implement update() method.
    }
}

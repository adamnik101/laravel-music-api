<?php

namespace App\Repositories\Implementations;

use App\Models\Artist;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;

class ArtistRepository implements ArtistRepositoryInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        $artists = Artist::query()->paginate(10);

        return $this->success("All artists", $artists);
    }

    function fetchOne(string $id): JsonResponse
    {
        $artist = Artist::query()->find($id);

        if (!$artist) return $this->error("No artist found", 404);

        return $this->success("Artist detail", $artist);
    }

    function delete(string $id): JsonResponse
    {
        // TODO: Implement delete() method.
    }

    function update(string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }
}

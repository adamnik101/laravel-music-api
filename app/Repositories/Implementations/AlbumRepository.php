<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\ArtistRequest;
use App\Models\Album;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class AlbumRepository implements AlbumRepositoryInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        try {
            $albums = Album::all();

            return $this->success("All albums", $albums);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    function fetchOne(string $id): JsonResponse
    {
        try {
            $album = Album::find($id);

            if(!$album) return $this->error("No album found.", 404);

            return $this->success("Album detail", $album);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }
    public function insert(ArtistRequest|FormRequest $request)
    {
        // TODO: Implement insert() method.
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

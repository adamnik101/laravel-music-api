<?php

namespace App\Repositories\Implementations;

use App\Models\Genre;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\JsonResponse;

class GenreRepository implements GenreRepositoryInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        $genres = Genre::query()->paginate(10);

        return $this->success('All genres', $genres);
    }

    function fetchOne(string $id): JsonResponse
    {
        $genre = Genre::query()->find($id);

        if(!$genre) return $this->error("No genre found.", 404);

        return $this->success("Genre detail", $genre);
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

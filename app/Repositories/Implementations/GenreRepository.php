<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class GenreRepository implements GenreRepositoryInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        $genres = Genre::all();

        return $this->success('All genres', $genres);
    }

    function fetchOne(string $id): JsonResponse
    {
        $genre = Genre::query()->find($id);

        if(!$genre) return $this->error("No genre found.", 404);

        return $this->success("Genre detail", $genre);
    }
    public function insert(array $data)
    {
        // TODO: Implement insert() method.
    }

    function delete(string $id): JsonResponse
    {
        // TODO: Implement delete() method.
    }

    public function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }
}

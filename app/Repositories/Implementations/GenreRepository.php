<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

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
        $genre = Genre::query()->with('playlists')->find($id);

        if(!$genre) return $this->error("No genre found.", 404);

        return $this->success("Genre detail", $genre);
    }
    public function insert(array $data)
    {
        try {
            $genre = new Genre();
            $genre->name = $data['name'];

            $genre->save();

            return $this->success("Added genre", 201);
        }
        catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error('Server error', 500);
        }
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

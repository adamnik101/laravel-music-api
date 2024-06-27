<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\GenreRequest;
use App\Models\Genre;
use App\Models\Playlist;
use App\Models\Track;
use App\Repositories\Interfaces\GenreInterface;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GenreRepository implements GenreInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        $genres = Genre::query()->whereHas('playlists')->get();

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
            $genre->name = $data[0]['name'];
            $genre->cover = 'cover';
            $genre->hex_color = '#fff';

            $genre->save();

            return $this->success("Added genre", 201);
        }
        catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error($exception->getMessage(), 500);
        }
    }

    function delete(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $genre = Genre::query()->find($id);

            if (!$genre) return $this->error('Genre not found', 400);

            Playlist::query()->where('genre_id', '=', $genre->id)->update([
                'genre_id' => null
            ]);

            Track::query()->where('genre_id', '=', $genre->id)->update([
                'genre_id' => null
            ]);

            $genre->delete();
            DB::commit();
            return $this->success('Genre has been deleted', null, 204);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->error($exception->getMessage(), 500);
        }
    }

    public function update(array $data, string $id): JsonResponse
    {
        try {
            $genre = Genre::query()->findOrFail($id);

            if(isset($data['name'])) {
                $genre->name = $data['name'];
            }

            $genre->save();

            return $this->success("Updated genre", 201);
        }
        catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error($exception->getMessage(), 500);
        }
    }
}

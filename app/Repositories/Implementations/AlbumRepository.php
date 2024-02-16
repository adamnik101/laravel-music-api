<?php

namespace App\Repositories\Implementations;

use App\Helpers\AlbumHelper;
use App\Http\Requests\AlbumRequest;
use App\Http\Requests\ArtistRequest;
use App\Models\Album;
use App\Models\Artist;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AlbumRepository implements AlbumRepositoryInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        try {
            $albums = Album::query()->withCount('tracks')->get();

            return $this->success("All albums", $albums);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    function fetchOne(string $id): JsonResponse
    {
        try {
            $album = Album::query()->withCount('tracks')
                            ->with(['tracks.features',
                                    'tracks.owner',
                                    'artist'])->find($id);

            if(!$album) return $this->error("No album found.", 404);

            return $this->success("Album detail", $album);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }
    public function insert(array $data): JsonResponse
    {
        try {
            if(!Artist::query()->find($data['artist_id']))  return $this->error('Provided artist does not exists', 422);

            $album = new Album();
            $album->name = $data['name'];
            $album->release_year = $data['release_year'];
            $album->cover = $data['cover'];
            $album->artist_id = $data['artist_id'];

            $album->save();

            return $this->success("Added album", $data, 201);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 500);
        }
    }

    function delete(string $id): JsonResponse
    {
        $album = Album::query()->find($id);

        if(!$album) return $this->error("No album found", 404);

        try {
            DB::beginTransaction();

            AlbumHelper::deleteAlbum($album);

            DB::commit();

            return $this->success("Deleted Album", 200);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->error("Server error", 500);
        }
    }

    public function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }
}

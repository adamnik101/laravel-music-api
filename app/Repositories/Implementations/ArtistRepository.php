<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
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
        $artist = Artist::query()
            ->with([
                'tracks.features', 'tracks.owner', 'albums',
                'features' => function ($query) {
                    $query->with(['owner', 'album', 'features']);
                }])
            ->withCount(['albums','tracks', 'features', 'followedBy'])->find($id);

        if (!$artist) return $this->error("No artist found", 404);

        $artist->featured_albums = $artist->features->pluck('album')->filter()->unique()->values()->toArray();

        return $this->success("Artist detail", $artist);
    }
    public function insert(array $data): JsonResponse
    {
        try {
            $name = $data['name'];
            $cover = $data['cover'];
            $artist = new Artist();
            $artist->name = $name;
            $artist->cover = $cover;

            $artist->save();

            return $this->success("Added artist", $name, 201);
        }
        catch (\Exception $exception) {
            return $this->error( $exception->getMessage(), 500);
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

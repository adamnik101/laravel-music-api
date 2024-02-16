<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\TrackRequest;
use App\Models\Track;
use App\Repositories\Interfaces\TrackRepositoryInterface;
use App\Serializers\TrackSerializer;
use App\Traits\ResponseAPI;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class TrackRepository implements TrackRepositoryInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        $tracks = Track::all();

        return $this->success("All tracks", $tracks);
    }

    function fetchOne(string $id): JsonResponse
    {
        $track = Track::query()->with(['features', 'likedBy'])->find($id);

        return $this->success("Track detail", TrackSerializer::serialize($track));
    }

    function insert(TrackRequest|FormRequest $request) : JsonResponse
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

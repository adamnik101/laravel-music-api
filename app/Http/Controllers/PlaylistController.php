<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertTracksToPlaylistRequest;
use App\Http\Requests\PlaylistRequest;
use App\Repositories\Interfaces\BaseInterface;
use App\Repositories\Interfaces\PlaylistInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlaylistController extends BaseController
{
    public function __construct(PlaylistInterface $repository)
    {
        parent::__construct($repository);
    }

    public function insert(PlaylistRequest $request)
    {
        return $this->repository->insert($request->validated());
    }

    public function update(string $id,PlaylistRequest $request): JsonResponse
    {
        return $this->repository->update($request->validated(), $id);
    }

    public function insertTracks(InsertTracksToPlaylistRequest $request, string $id) : JsonResponse
    {
        return $this->repository->insertTracks($request->validated('tracks'), $id, $request->get('confirm'));
    }

    public function removeTrack(string $playlist, string $track)
    {
        return $this->repository->removeTrack($playlist, $track);
    }
}

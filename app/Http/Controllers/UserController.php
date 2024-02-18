<?php

namespace App\Http\Controllers;

use App\Http\Requests\SingleUuidRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController {

    public function __construct(UserRepositoryInterface $userRepository) {
        parent::__construct($userRepository);
    }

    public function insert(UserRequest $request)
    {
        return $this->repository->insert($request->validated());
    }
    public function update(UserRequest $request, string $id): JsonResponse
    {
        return $this->repository->update($request->validated(), $id);
    }

    public function fetchUserLikedTracks()
    {
        return $this->repository->fetchUserLikedTracks();
    }
    public function fetchUserLikedAlbums()
    {
        return $this->repository->fetchUserLikedAlbums();
    }
    public function fetchUserLikedArtists()
    {
        return $this->repository->fetchUserLikedArtists();
    }

    public function saveTrack(SingleUuidRequest $request)
    {
        return $this->repository->saveTrack($request->validated('uuid'));
    }
    public function saveAlbum(SingleUuidRequest $request)
    {
        return $this->repository->saveAlbum($request->validated('uuid'));
    }

    public function saveArtist(SingleUuidRequest $request)
    {
        return $this->repository->saveArtist($request->validated('uuid'));
    }
    public function unsaveTrack(string $id)
    {
        return $this->repository->unsaveTrack($id);
    }
    public function unsaveAlbum(string $id)
    {
        return $this->repository->unsaveAlbum($id);
    }
    public function unsaveArtist(string $id)
    {
        return $this->repository->unsaveArtist($id);
    }
}

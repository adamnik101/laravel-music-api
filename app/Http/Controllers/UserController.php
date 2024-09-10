<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageRequest;
use App\Http\Requests\SingleUuidRequest;
use App\Http\Requests\UpdateSettingsRequest;
use App\Http\Requests\UpdateUsernameRequest;
use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\UserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController {

    public function __construct(UserInterface $userRepository) {
        parent::__construct($userRepository);
    }

    public function insert($request) : JsonResponse
    {
        return $this->repository->insert($request->validated());
    }
    public function update(UserRequest $request, string $id): JsonResponse
    {
        return $this->repository->update($request->validated(), $id);
    }

    public function fetchUserLikedTracks() : JsonResponse
    {
        return $this->repository->fetchUserLikedTracks();
    }
    public function fetchUserLikedAlbums() : JsonResponse
    {
        return $this->repository->fetchUserLikedAlbums();
    }
    public function fetchUserLikedArtists() : JsonResponse
    {
        return $this->repository->fetchUserLikedArtists();
    }
    public function fetchRecentlyPlayedTracks() : JsonResponse
    {
        return $this->repository->fetchRecentlyPlayedTracks();
    }
    public function saveTrack(SingleUuidRequest $request) : JsonResponse
    {
        return $this->repository->saveTrack($request->validated('uuid'));
    }
    public function saveAlbum(SingleUuidRequest $request): JsonResponse
    {
        return $this->repository->saveAlbum($request->validated('uuid'));
    }

    public function saveArtist(SingleUuidRequest $request): JsonResponse
    {
        return $this->repository->saveArtist($request->validated('uuid'));
    }
    public function unsaveTrack(string $id): JsonResponse
    {
        return $this->repository->unsaveTrack($id);
    }
    public function unsaveAlbum(string $id): JsonResponse
    {
        return $this->repository->unsaveAlbum($id);
    }
    public function unsaveArtist(string $id): JsonResponse
    {
        return $this->repository->unsaveArtist($id);
    }

    public function updateSettings(UpdateSettingsRequest $request): JsonResponse
    {
        return $this->repository->updateSettings($request->validated());
    }
    public function updateUsername(UpdateUsernameRequest $request) : JsonResponse
    {
        return $this->repository->updateUsername($request->validated('username'));
    }
    public function updateCover(ImageRequest $request) : JsonResponse
    {
        return $this->repository->updateCover($request->validated('image'));
    }
}

<?php

namespace App\Repositories\Implementations;

use App\Helpers\PlaylistHelper;
use App\Http\Requests\PlaylistRequest;
use App\Models\Playlist;
use App\Repositories\Interfaces\PlaylistRepositoryInterface;
use App\Traits\ResponseAPI;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaylistRepository implements PlaylistRepositoryInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        $playlists = Playlist::query()->paginate(10);

        return $this->success("All playlists with pagination", $playlists);
    }

    function fetchOne(string $id): JsonResponse
    {
        $playlist = Playlist::query()->with('tracks')->find($id);

        if (!$playlist) return $this->error("No playlist found.", 404);

        return $this->success("Playlist detail", $playlist);
    }
    function insert(array $data): JsonResponse
    {
        try {
            $title = $data['title'];
            $description = $data['description'];

            $playlist = new Playlist();
            $playlist->title = $title;
            $playlist->user_id = Auth::user()->getAuthIdentifier();

            if($description) $playlist->description = $description;

            $playlist->save();

            return $this->success('Playlist added', $data, 201);
        }
        catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error("Server error", 500);
        }
    }
    function delete(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $playlist = Playlist::query()->find($id);
            if (!$playlist) return $this->error('Playlist not found', 404);

            PlaylistHelper::deletePlaylist($playlist);

            DB::commit();
            return $this->success("Playlist deleted", 204);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->error("Server error", 500);
        }
    }

    public function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }
}

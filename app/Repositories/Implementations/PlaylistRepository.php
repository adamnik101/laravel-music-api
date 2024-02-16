<?php

namespace App\Repositories\Implementations;

use App\Helpers\PlaylistHelper;
use App\Http\Requests\PlaylistRequest;
use App\Models\Playlist;
use App\Models\User;
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
        $user_id = Auth::user()->getAuthIdentifier();
        $user = User::query()->find($user_id);

        if (!$user) return $this->error('Not authorized', 401);


        $playlists = $user->load(['playlists' => function ($query) {
            $query->withCount('tracks');
        }])->playlists;

        return $this->success("All playlists with pagination", $playlists);
    }

    function fetchOne(string $id): JsonResponse
    {
        $playlist = Playlist::query()->withCount('tracks')
            ->with(['tracks' => function ($query) {
                $query->with(['owner', 'features', 'album']);
            }])
            ->find($id);

        if (!$playlist) return $this->error("No playlist found.", 404);
        $playlist->latest_added = $playlist->tracks()->latest('playlist_track.created_at')->first()->created_at;

//        $playlist->tracks = $playlist->tracks()->with(['owner', 'features', 'album'])->paginate(50);

//        $playlist->latest_added = $playlist->tracks()->latest('playlist_track.created_at')->first()->created_at;

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

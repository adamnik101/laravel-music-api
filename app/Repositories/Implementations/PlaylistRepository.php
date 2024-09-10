<?php

namespace App\Repositories\Implementations;

use App\Helpers\ImageHelper;
use App\Helpers\PlaylistHelper;
use App\Http\Requests\InsertTracksToPlaylistRequest;
use App\Http\Requests\PlaylistRequest;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use App\Repositories\Interfaces\PlaylistInterface;
use App\Traits\ResponseAPI;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaylistRepository implements PlaylistInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        $user_id = Auth::user()->getAuthIdentifier();
        $user = User::query()->find($user_id);

        if (!$user) return $this->error('Not authorized', 401);


        $playlists = $user->load(['playlists' => function ($query) {
            $query->withCount('tracks')
            ->orderByDesc('created_at');
        }])->playlists;

        return $this->success("All playlists", $playlists);
    }

    function fetchOne(string $id): JsonResponse
    {
        $playlist = Playlist::query()->with('tracks')->withCount('tracks')->find($id);

        if (!$playlist) return $this->error("No playlist found.", 404);

        $playlist->latest_added = $playlist->tracks->last() ? $playlist->tracks->last()->pivot->created_at : $playlist->updated_at;

        return $this->success("Playlist detail", $playlist);
    }
    function insert(array $data): JsonResponse
    {
        try {
            $title = $data['title'];
            $description = $data['description'] ?? null;
            $image = $data['image'] ?? null;


            $playlist = new Playlist();
            $playlist->title = $title;
            $playlist->user_id = Auth::user()->getAuthIdentifier();
            if ($image) {
                $playlist->image_url = ImageHelper::uploadImage($image, 'playlists');
            }
            if($description) $playlist->description = $description;

            $playlist->save();

            return $this->success('Added to your library.', $playlist, 201);
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
            return $this->success('Deleted playlist', null, 204);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->error("Server error", 500);
        }
    }

    public function update(array $data, string $id): JsonResponse
    {
        $playlist = Playlist::query()
            ->with('tracks')->withCount('tracks')
            ->where('user_id', '=', Auth::user()->getAuthIdentifier())->find($id);

        if (!$playlist) return $this->error('Not authorized', 401);

        $hasImage = isset($data['image']);

        if ($hasImage) {
            $image = $data['image'];
            $image_url = ImageHelper::uploadImage($image, 'playlists');
            $playlist->image_url = $image_url;
        }
        else if (isset($data['remove_image'])){
            $playlist->image_url = null;
        }

        $playlist->title  = $data['title'];
        if(isset($data['description'])) {
            $playlist->description = $data['description'];
        }
        $playlist->updated_at = now();

        $playlist->save();

        $playlist->latest_added = $playlist->tracks->last() ? $playlist->tracks->last()->pivot->created_at : $playlist->updated_at;

        return $this->success('Updated playlist', $playlist, 201);
    }

    public function insertTracks(array $trackIds, string $id, ?bool $confirm): JsonResponse
    {
        $tracks = Track::query()->whereIn('id', $trackIds)->get();
        if (!$tracks) return $this->error('Track does not exist', 400);

        $playlist = Playlist::query()->find($id);
        if (!$playlist) return $this->error("Playlist not found", 400);

        $successResponse = [
            'playlist_id' => $id,
            'added_count' => count($tracks),
            'message' => 'Added to \''.$playlist->title.'\''
        ];
        $errorResponse = [
            'playlist_id' => $id,
            'all_tracks_id' => $tracks->pluck('id')
        ];
        if($confirm) {
            try {
                DB::beginTransaction();
                PlaylistHelper::addTracks($playlist, $trackIds);
                DB::commit();

                return $this->success("Added ".count($trackIds)." tracks", $successResponse,201);
            }
            catch (\Exception $exception) {
                DB::rollBack();
            }
        }

        $tracksAlreadyInPlaylist = $playlist->tracks()->findMany($trackIds)->pluck('id')->unique();
        if(count($tracksAlreadyInPlaylist) == 0) {
            PlaylistHelper::addTracks($playlist, $trackIds);
            return $this->success("Success", $successResponse, 201);
        }

        $errorResponse['tracks_already_in_playlist'] = $tracksAlreadyInPlaylist;

        if(count($tracksAlreadyInPlaylist) < count($trackIds)) {
            $errorResponse['message'] = 'Some already added';
            $errorResponse['content'] = 'Some of these are already in your \''.$playlist->title.'\' playlist';
            $errorResponse['actions'] = ['Add all', 'Add new ones'];
            $errorResponse['status'] = 'warning-some';

            return $this->error("Some already added", 422, $errorResponse);
        }

        if(count($tracksAlreadyInPlaylist) == count($trackIds)) {
            $errorResponse['message'] = 'Already added';
            $errorResponse['actions'] = ['Add anyway', 'Don\'t add'];
            $errorResponse['status'] = 'warning-all';

            if(count($tracks) === 1) {
                    $errorResponse['content'] = 'This track is already in your \''.$playlist->title.'\' playlist.';
            } else {
                $errorResponse['content'] = 'These are already added in your \''.$playlist->title.'\' playlist.';
            }

            return $this->error("Already added", 422, $errorResponse);
        }

        return $this->success("Added track", $playlist, 201);
    }

    public function removeTrack(string $playlist, string $track): JsonResponse
    {
        $playlist = Playlist::query()->find($playlist);

        if (!$playlist) return $this->error('Playlist not found', 400);

        $user = Auth::user()->getAuthIdentifier();

        $userOwnsPlaylist = $playlist->where('user_id', '=', $user)->first();

        if (!$userOwnsPlaylist) return $this->error('Not authorized', 401);

        $trackToDetach = $playlist->tracks()->wherePivot('id', $track)->first();

        $playlist->tracks()->wherePivot('id', $track)->detach($trackToDetach->id);

        return $this->success("Removed track from playlist", [$trackToDetach, $track], 204);
    }
}

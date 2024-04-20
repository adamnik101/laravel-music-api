<?php

namespace App\Repositories\Implementations;

use App\Helpers\ImageHelper;
use App\Helpers\UserHelper;
use App\Models\Album;
use App\Models\Track;
use App\Models\TrackPlay;
use App\Models\User;
use App\Repositories\Interfaces\UserInterface;
use App\Traits\ResponseAPI;
use Illuminate\Http\File;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        try {
            $users = User::query()->with('roles')->paginate(10);

            return $this->success("All users with pagination", $users);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    function fetchOne(string $id): JsonResponse
    {
        try {
            $user = User::query()->find($id);

            if (!$user) return $this->error("No user found.", 404);

            return $this->success("User Detail", $user);
        }
        catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->error($exception->getMessage(), 500);
        }
    }

    function insert(array $data)
    {
        // TODO: Implement insert() method.
    }
    function delete(string $id): JsonResponse
    {
        try{
            DB::beginTransaction();
            $userToDelete = User::query()->find($id);

            if(!$userToDelete) return $this->error('No user found', 400);
            if ($userToDelete->id == Auth::user()->getAuthIdentifier()) return $this->error('Cannot delete your own account', 422);

            UserHelper::deleteUserRelatedData($userToDelete);

            UserHelper::deleteUser($userToDelete);

            DB::commit();

            return $this->success('User deleted successfully.', $userToDelete, 200);
        }catch (\Exception $exception) {
            DB::rollBack();
            Log::critical($exception->getMessage());
            return $this->error("Server error", 500);
        }
    }

    public function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }

    public function fetchUserLikedTracks(): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('Not authorized', 401);

        $tracks = $user->likedTracks()->get();

        return $this->success('User liked tracks', $tracks);
    }
    public function fetchUserLikedAlbums(): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());

        if (!$user) return $this->error('Not authorized', 401);

        $albums = $user->likedAlbums()->get();

        return $this->success('Fetch user liked albums', $albums);
    }
    public function fetchUserLikedArtists(): JsonResponse
    {
        $user = User::query()->with('followings')->find(Auth::user()->getAuthIdentifier());

        if(!$user) return $this->error('Not authorized', 401);

        return $this->success('Fetch user followings', $user->followings);
    }
    public function fetchRecentlyPlayedTracks(): JsonResponse
    {
        $user = \auth('sanctum')->user();

        $tracks = TrackPlay::query()->where('user_id', $user->getAuthIdentifier())
            ->select('track_id', DB::raw('MAX(created_at) as latest_play'))
            ->orderByDesc('latest_play')
            ->groupBy("track_id")
            ->with(['track.owner', 'track.features', 'track.album'])
            ->take(5)->get()->pluck('track');

        return $this->success('Recently played tracks for user', $tracks);
    }

    function saveTrack(string $track): JsonResponse
    {
        $trackExists = Track::query()->find($track);
        if (!$trackExists) return $this->error('Track not found', 400);

        $user = User::query()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('User not found', 400);

        $user->likedTracks()->syncWithoutDetaching([$track => [
            "created_at" => now(),
            "updated_at" => now()
        ]]);
        return $this->success("Added to Liked", $track);
    }

    function saveAlbum(string $album): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('Not authorized', 401);

        $user->likedAlbums()->syncWithoutDetaching([$album => [
            'created_at' => now(),
            'updated_at' => now()
        ]]);

        return $this->success("Added to Liked", $user->likedAlbums, 201);
    }

    function saveArtist(string $artist): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());

        $user->followings()->syncWithoutDetaching([$artist => [
            "created_at" => now(),
            "updated_at" => now()
        ]]);

        return $this->success("Saved to Library", $artist, 201);
    }
    public function unsaveTrack(string $track): JsonResponse
    {
        $trackExists = Track::query()->find($track);
        if (!$trackExists) return $this->error('Track not found', 400);

        $user = User::query()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('Not authorized', 401);

        $user->likedTracks()->detach($track);

        return $this->success('Unsaved track', $track, 204);
    }
    public function unsaveAlbum(string $album): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('Not authorized', 401);

        $albumExists = Album::query()->find($album);
        if (!$albumExists) return $this->error('Track not found', 400);

        $user->likedAlbums()->detach($album);

        return $this->success('Unsaved album', $user->likedAlbums, 204);
    }
    public function unsaveArtist(string $artist): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('Not authorized', 401);

        $user->followings()->detach($artist);

        return $this->success('Removed artist from library',null, 204);
    }
    public function updateSettings(array $data) : JsonResponse
    {
        $user = User::query()->with('settings')->find(Auth::user()->getAuthIdentifier());
        if (!$user) return $this->error('Not authorized', 401);

        $user->settings()->update([
            'explicit' => $data['value']
        ]);

        return $this->success('Updated settings', ['id' => $user->settings->id, 'explicit' => $data['value']]);
    }

    public function updateUsername(string $username) : JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());

        $user->username = $username;

        $user->save();

        return $this->success('Updated username', $user);
    }
    public function updateCover(UploadedFile $file): JsonResponse
    {
        $user = User::query()->find(Auth::user()->getAuthIdentifier());

        if (!$user) return $this->error('Not authorized', 401);

        $path = ImageHelper::uploadImage($file);
        $user->cover = $path;

        $user->save();

        return $this->success('Updated profile image', $user);
    }
}

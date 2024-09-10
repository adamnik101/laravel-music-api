<?php

namespace App\Repositories\Implementations;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Playlist;
use App\Models\Track;
use App\Models\User;
use App\Repositories\Interfaces\AdminInterface;
use App\Traits\ResponseAPI;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminRepository implements AdminInterface
{
    use ResponseAPI;
    public function dashboard(): JsonResponse
    {
        $responseData = [];

        $responseData['user_count'] = User::query()->count();
        $responseData['playlist_count'] = Playlist::query()->count();
        $responseData['genre_count'] = Genre::query()->count();
        $responseData['track_count'] = Track::query()->count();
        $responseData['album_count'] = Album::query()->count();
        $responseData['artist_count'] = Artist::query()->count();

        $responseData['liked_per_user'] = round(User::query()->withCount('likedTracks')->get()->average('liked_tracks_count'));
        $responseData['tracks_per_playlist'] = round(Playlist::query()->withCount('tracks')->get()->average('tracks_count'));

        return $this->success('Admin dashboard', $responseData);
    }

    public function artists(): JsonResponse
    {
        return $this->success('All Artists', Artist::with('albums')->get());
    }

    public function albums(): JsonResponse
    {
        return $this->success('All Albums', Album::query()->get());

    }

    public function genres(): JsonResponse
    {
        return $this->success('All Genres paginate', Genre::query()->paginate(10));

    }
}

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


        //stats
        $responseData['average_count_per_user'] = User::query()
            ->selectRaw('AVG(playlist_counts.playlist_count) AS playlists')
            ->leftJoinSub(
                Playlist::query()->selectRaw('COUNT(user_id) AS playlist_count, user_id')->groupBy('user_id'),
                'playlist_counts',
                'users.id',
                '=',
                'playlist_counts.user_id')
            ->first();

        $averagePlaylistsPerUser = User::query()->select(DB::raw('AVG(playlist_count) as avg_playlists_per_user'))
            ->fromSub(function ($query) {
                $query->selectRaw('count(p.user_id) as playlist_count')
                    ->from('users as u')
                    ->join('playlists as p', 'u.id', '=', 'p.user_id')
                    ->groupBy('u.id');
            }, 'sub')
            ->first();
        $averageTracksPerPlaylist = Playlist::query()
            ->selectRaw('avg(tracks_count) as tracks_avg')
            ->fromSub(function (Builder $query) {
                $query->selectRaw('count(pt.id) as tracks_count')
                    ->from('playlists as p')
                    ->leftJoin('playlist_track as pt', 'p.id', '=', 'pt.playlist_id')
                    ->groupBy('p.id');
            }, 'sub')->first();

        $responseData['average_playlists_per_user'] = $averagePlaylistsPerUser;
        $responseData['average_tracks_per_playlist'] = $averageTracksPerPlaylist;

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

<?php

namespace App\Repositories\Implementations;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Genre;
use App\Models\Track;
use App\Models\User;
use App\Repositories\Interfaces\SearchInterface;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class SearchRepository implements SearchInterface
{
    use ResponseAPI;
    function parseDate(string $date) {
        return Carbon::parse(preg_replace('/\(.*\)/', '', $date));
    }
    function searchTimestamps(Builder $builder, array $query): void
    {
        if(isset($query['createdFrom'])) {
            $from = $query['createdFrom'];
            $builder->where('created_at', '>=', $this->parseDate($from));
        }
        if(isset($query['createdTo'])) {
            $to = $query['createdTo'];
            $builder->where('created_at', '<=', $this->parseDate($to));
        }
        if(isset($query['updatedFrom'])) {
            $from = $query['updatedFrom'];
            $builder->where('updated_at', '>=', $this->parseDate($from));
        }
        if(isset($query['updatedTo'])) {
            $to = $query['updatedTo'];
            $builder->where('updated_at', '<=', $this->parseDate($to));
        }
    }
    function search(string $query): JsonResponse
    {
            if($query != "") {
            $tracks = Track::query()->where(function ($track) use ($query) {
                $track->where('title', 'like', "%$query%")
                    ->orWhereHas('owner', function ($artist) use ($query){
                        $artist->where('name', 'like', "%$query%");
                    });
            })->paginate(10, ['*'], 'track_page')->appends(['query' => $query]);

            $albums = Album::query()->where('name', 'like', "%$query%")->withCount('tracks')->paginate(5, ['*'], 'album_page')->appends(['query'=> $query]);
            $artists = Artist::query()->where('name', 'like', "%$query%")->withCount('followedBy')->paginate(5, ['*'], 'artist_page')->appends(['query'=> $query]);

            return $this->success('Search',
                ['tracks' => $tracks,
                'artists' => $artists,
                'albums' => $albums]);
        }
        return $this->success('Search', '');
    }

    function searchTracks(array $query): JsonResponse
    {
        $tracks = Track::query();
        if (isset($query['title'])) {
            $title = $query['title'];
            $tracks->where('title', 'like', '%' . $title . '%');
        }

        if (isset($query['owner'])) {
            $owner = $query['owner'];
            $tracks->whereHas('owner', function ($subquery) use ($owner) {
                $subquery->where('name', 'like', '%' . $owner . '%');
            });
        }

        if (isset($query['album'])) {
            $album = $query['album'];
            $tracks->whereHas('album', function ($subquery) use ($album){
                $subquery->where('name', 'like', '%' . $album . '%');
            });
        }

        if (isset($query['featuring'])) {
            $featuring = $query['featuring'];
            $featuring = explode(',', trim($featuring));
            $featuring = array_map('trim', $featuring);

            $tracks->whereHas('featuring', function ($subquery) use ($featuring) {
                $subquery->whereHas('artist', function ($artistQuery) use ($featuring){
                    $artistQuery->whereIn('name', $featuring);
                });
            });
        }

        if (isset($query['playsFrom'])) {
            $playsFrom = $query['playsFrom'];

            $tracks->whereHas('trackPlays', function ($query) use ($playsFrom){
                $query->havingRaw('COUNT(id) >= ?', [$playsFrom]);
            });
        }

        if (isset($query['playsTo'])) {
            $playsTo = $query['playsTo'];

            $tracks->whereHas('trackPlays', function ($query) use ($playsTo){
                $query->havingRaw('COUNT(id) <= ?', [$playsTo]);
            });
        }

        if (isset($query['explicit'])) {
            $explicit = $query['explicit'];
            $tracks->where('explicit', $explicit);
        }

        $this->searchTimestamps($tracks, $query);

        $result = $tracks->paginate(10);

        return $this->success('Tracks search', $result);
    }

    function searchGenres(array $query): JsonResponse
    {
        // TODO: Implement searchGenres() method.
    }

    function searchArtists(array $query): JsonResponse
    {

        $artists = Artist::query()->withCount('tracks');
        if(isset($query['name'])) {
            $name = $query['name'];
            $artists->where('name', 'like', '%'.$name.'%');
        }
        if(isset($query['tracksCountFrom'])) {
            $from = $query['tracksCountFrom'];
            $artists->whereHas('tracks', function ($query) use ($from){
                $query->havingRaw('COUNT(id) >= ?', [$from]);
            });
        }
        if(isset($query['tracksCountTo'])) {
            $to = $query['tracksCountTo'];
            $artists->whereHas('tracks', function ($query) use ($to) {
                $query->havingRaw('COUNT(id) <= ?', [$to]);
            });
        }
        $this->searchTimestamps($artists, $query);

        $result = $artists->paginate(10);

        return $this->success('Search artists with pagination', $result);

    }

    function searchAlbums(array $query): JsonResponse
    {
        $albums = Album::withCount('tracks')->with('artist');
        if(isset($query['name'])) {
            $name = $query['name'];
            $albums->where('name', 'like', '%'.$name.'%');
        }
        if(isset($query['master'])) {
            $master = $query['master'];
            $albums->whereHas('artist', function ($query) use ($master){
                $query->where('name', 'like', '%'.$master.'%');
            });
        }
        if(isset($query['tracksCountFrom'])){
            $from = $query['tracksCountFrom'];
            $albums->whereHas('tracks', function ($subquery) use ($from) {
                $subquery->havingRaw('COUNT(id) >= ?', [$from]);
            });
        }
        if(isset($query['trackCountTo'])) {
            $to = $query['trackCountTo'];
            $albums->whereHas('tracks', function ($subquery) use ($to){
                $subquery->havingRaw('COUNT(id) <= ?', [$to]);
            });
        }
        if(isset($query['releaseYear'])) {
            $year = $query['releaseYear'];
            $albums->where('release_year', $year);
        }

        $this->searchTimestamps($albums, $query);

        $result = $albums->paginate(10);

        return $this->success('Search albums with pagination', $result);
    }

    function searchUsers(array $query): JsonResponse
    {
        $users = User::query();

        if (isset($query['username'])) {
            $username = $query['username'];
            $users->where('username', 'like', '%'.$username.'%');
        }
        if (isset($query['email'])) {
            $email = $query['email'];
            $users->where('email', 'like', '%'.$email.'%');
        }
        $this->searchTimestamps($users, $query);

        $result = $users->paginate(10);
        return $this->success('Search users with pagination', $result);
    }
}

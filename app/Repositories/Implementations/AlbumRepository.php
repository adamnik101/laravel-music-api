<?php

namespace App\Repositories\Implementations;

use App\Helpers\AlbumHelper;
use App\Helpers\ImageHelper;
use App\Http\Requests\AlbumRequest;
use App\Http\Requests\ArtistRequest;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Track;
use App\Models\TrackPlay;
use App\Repositories\Interfaces\AlbumInterface;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AlbumRepository implements AlbumInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        try {
            $albums = Album::query()->with('artist')->withCount('tracks')->paginate(10);

            return $this->success("All albums with pagination", $albums);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }

    function fetchOne(string $id): JsonResponse
    {
        try {
            $album = Album::query()->withCount('tracks')
                            ->with(['artist', 'tracks'])->find($id);

            if(!$album) return $this->error("No album found.", 404);

            return $this->success("Album detail", $album);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), $exception->getCode());
        }
    }
    public function insert(array $data): JsonResponse
    {
        try {
            if(!Artist::query()->find($data['artist_id']))  return $this->error('Provided artist does not exists', 422);

            $imagePath = ImageHelper::uploadImage($data['cover'], 'albums');

            $album = new Album();
            $album->name = $data['name'];
            $album->release_year = $data['release_year'];
            $album->cover = $imagePath;
            $album->artist_id = $data['artist_id'];

            $album->save();

            return $this->success("Added album", $data, 201);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 500);
        }
    }

    function delete(string $id): JsonResponse
    {
        $album = Album::query()->find($id);

        if(!$album) return $this->error("No album found", 404);

        try {
            DB::beginTransaction();

            AlbumHelper::deleteAlbum($album);

            DB::commit();

            return $this->success("Deleted Album", 200);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->error("Server error", 500);
        }
    }

    public function update(array $data, string $id): JsonResponse
    {
        try {
            if(!Artist::query()->find($data['artist_id']))  return $this->error('Provided artist does not exists', 422);

            $imagePath = "";
            if(isset($data['cover'])) {

                $imagePath = ImageHelper::uploadImage($data['cover'], 'albums');
            }

            $album = Album::query()->findOrFail($id);
            $album->name = $data['name'];
            $album->release_year = $data['release_year'];
            if(isset($data['cover'])) {

                $album->cover = $imagePath;
            }
            $album->artist_id = $data['artist_id'];

            $album->save();

            return $this->success("Updated album", $data, 204);
        }
        catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 500);
        }
    }

    public function newReleases() : JsonResponse
    {
        $newReleasedAlbums = Album::query()->withCount('tracks')->whereHas('tracks')->orderByDesc('created_at')->take(6)->get();

        return $this->success('Album new releases', $newReleasedAlbums);
    }
    public function trending() : JsonResponse
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $albums = Album::query()->selectRaw('artists.id as artist_id, artists.name as artist_name, COUNT(track_plays.track_id) as track_count, count(distinct tracks.id) as tracks_count, albums.id, albums.name, albums.cover, albums.release_year')
            ->join('tracks', 'albums.id', '=', 'tracks.album_id')
            ->join('track_plays', 'tracks.id', '=', 'track_plays.track_id')
            ->join('artists', 'artists.id', '=', 'albums.artist_id')
            ->whereBetween('track_plays.created_at', [$sevenDaysAgo, now()])
            ->groupBy('albums.id', 'albums.name', 'albums.cover', 'albums.release_year', 'artists.id', 'artists.name')
            ->orderByDesc(DB::raw('track_count'))
            ->take(6)
            ->get();

        $serialize = [];
        foreach ($albums as $album) {
            $serialize[] = [
                    'id' => $album->id,
                    'name' => $album->name,
                    'cover' => $album->cover,
                    'tracks_count' => $album->tracks_count,
                    'artist' => [
                        'id' => $album->artist_id,
                        'name' => $album->artist_name]

            ];
        }
        return $this->success('Trending albums', $serialize);
    }
}

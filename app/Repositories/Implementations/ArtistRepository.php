<?php

namespace App\Repositories\Implementations;

use App\Helpers\ArtistHelper;
use App\Http\Requests\ArtistRequest;
use App\Models\Artist;
use App\Repositories\Interfaces\ArtistInterface;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class ArtistRepository implements ArtistInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        $artists = Artist::query()->with('albums')->get();

        return $this->success("All artists", $artists);
    }

    function fetchOne(string $id): JsonResponse
    {
        $artist = Artist::query()
            ->with(['tracks', 'features', 'albums'])
            ->withCount(['albums','tracks', 'features', 'followedBy'])->find($id);

        if (!$artist) return $this->error("No artist found", 404);
        $artist->monthly_listeners = ArtistHelper::getMonthlyListeners($artist);
        $artist->featured_albums = $artist->features->pluck('album')->filter()->unique()->values()->toArray();

        return $this->success("Artist detail", $artist);
    }
    public function insert(array $data): JsonResponse
    {
        try {
            $name = $data['name'];
            $cover = $data['cover'];
            $artist = new Artist();
            $artist->name = $name;
            $artist->cover = $cover;

            $artist->save();

            return $this->success("Added artist", $name, 201);
        }
        catch (\Exception $exception) {
            return $this->error( $exception->getMessage(), 500);
        }
    }

    function delete(string $id): JsonResponse
    {
        // TODO: Implement delete() method.
    }

    public function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }
    public function trending() : JsonResponse
    {
        $now = Carbon::now();
        $sevenDays = $now->copy()->subDays(7);

        $popularArtists = Artist::query()->select('artists.id', 'artists.name', 'artists.cover')
            ->join('tracks', 'artists.id', '=', 'tracks.owner_id')
            ->join('track_plays', 'tracks.id', '=', 'track_plays.track_id')
            ->whereBetween('track_plays.created_at', [$sevenDays, $now])
            ->groupBy('artists.id', 'artists.name', 'artists.cover')
            ->orderByRaw('COUNT(track_plays.id) DESC')->withCount('followedBy')->take(9)->get();

        return $this->success('Trending artists', $popularArtists);
    }
}

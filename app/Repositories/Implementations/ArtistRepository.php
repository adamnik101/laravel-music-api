<?php

namespace App\Repositories\Implementations;

use App\Helpers\ArtistHelper;
use App\Helpers\ImageHelper;
use App\Http\Requests\ArtistRequest;
use App\Http\Requests\ManyUuidsRequest;
use App\Models\Artist;
use App\Repositories\Interfaces\ArtistInterface;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ArtistRepository implements ArtistInterface
{
    use ResponseAPI;
    function fetchAll(): JsonResponse
    {
        $artists = Artist::query()->with('albums')->paginate(10);

        return $this->success("All artists", $artists);
    }

    function fetchOne(string $id): JsonResponse
    {
        $artist = Artist::query()
            ->with(['tracks' => function ($query) {
                $query->withCount('likedBy');
            }, 'features', 'albums'])
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
            $artist = new Artist();
            $artist->name = $name;
            $artist->verified = $data['verified'];

            if(isset($data['cover'])) {
                $artist->cover = ImageHelper::uploadImage($data['cover'], 'artists');
            } else {
                $artist->cover = 'default.jpg';
            }
            $artist->save();

            return $this->success("Added artist", $name, 201);
        }
        catch (\Exception $exception) {
            return $this->error( $exception->getMessage(), 500);
        }
    }

    function delete(string $id): JsonResponse
    {
        $artist = Artist::query()->find($id);

        try {
            DB::beginTransaction();

            $artist->delete();

            DB::commit();

            return $this->success("Deleted Artist", $artist);
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return $this->error("Server error", 500);
        }
    }

    function deleteMany(ManyUuidsRequest $request): JsonResponse
    {
        $ids = $request->get('data');

        try{
            Artist::destroy($ids);
        }
        catch (\Exception $exception) {
            return $this->error('Error on deleting', 422);
        }

        return $this->success('Delete Many', []);
    }
    public function update(array $data, string $id): JsonResponse
    {
        $artist = Artist::query()->findOrFail($id);
        try {
            $name = $data['name'];
            if(isset($data['cover'])) {
                $cover = $data['cover'];
                $artist->cover = ImageHelper::uploadImage($cover, 'artists');
            }
            $artist->name = $name;
            $artist->save();

            return $this->success("Updated artist", $name, 201);
        }
        catch (\Exception $exception) {
            return $this->error( $exception->getMessage(), 500);
        }
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

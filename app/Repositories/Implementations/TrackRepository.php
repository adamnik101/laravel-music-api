<?php

namespace App\Repositories\Implementations;

use App\Http\Requests\TrackRequest;
use App\Models\Track;
use App\Models\TrackPlay;
use App\Repositories\Interfaces\TrackInterface;
use App\Serializers\TrackSerializer;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrackRepository implements TrackInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        $tracks = Track::paginate(10);

        return $this->success("All tracks with pagination", $tracks);
    }

    function fetchOne(string $id): JsonResponse
    {
        $track = Track::query()->with(['likedBy'])->find($id);

        $user = \auth('sanctum')->user();

        TrackPlay::query()->insert([
            'user_id' => $user?->getAuthIdentifier(),
            'track_id' => $track->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        return $this->success("Track detail", TrackSerializer::serialize($track));
    }

    function insert(array $data) : JsonResponse
    {
        // TODO: Implement insert() method.
    }

    function delete(string $id): JsonResponse
    {
        // TODO: Implement delete() method.
    }

    public function update(array $data, string $id): JsonResponse
    {
        // TODO: Implement update() method.
    }


    public function newReleases(): JsonResponse
    {
        $tracks = Track::query()->with(['owner', 'features', 'album'])->orderByDesc('created_at')->take(6)->get();

        return $this->success('Tracks new releases', $tracks);
    }
    public function trending(): JsonResponse
    {
        $now = Carbon::now();
        $sevenDays = $now->copy()->subDays(30);

        $trending = TrackPlay::query()->select('track_id')
            ->whereBetween('created_at', [$sevenDays, $now])
            ->groupBy('track_id')
            ->havingRaw('COUNT(track_id) > 5') // pustana vise od n puta ukupno
            ->havingRaw('COUNT(DISTINCT user_id) > 0') // vise od n korisnika
            ->orderByDesc(\DB::raw('COUNT(track_id)'))
            ->take(10);

        $tracks = Track::query()->whereIn('id', $trending)->get();

        return $this->success('Trending tracks', $tracks);
    }
}

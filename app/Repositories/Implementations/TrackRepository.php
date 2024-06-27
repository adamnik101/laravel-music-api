<?php

namespace App\Repositories\Implementations;

use App\Helpers\ImageHelper;
use App\Helpers\TrackHelper;
use App\Helpers\UserHelper;
use App\Http\Requests\TrackRequest;
use App\Models\Track;
use App\Models\TrackPlay;
use App\Repositories\Interfaces\TrackInterface;
use App\Serializers\TrackSerializer;
use App\Traits\ResponseAPI;
use Carbon\Carbon;
use http\Client\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use wapmorgan\Mp3Info\Mp3Info;

class TrackRepository implements TrackInterface
{
    use ResponseAPI;

    function fetchAll(): JsonResponse
    {
        $tracks = Track::query()->paginate(10);

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
        try {
            DB::beginTransaction();
            $imagePath = ImageHelper::uploadImage($data['cover'], 'tracks');
            $trackPath = TrackHelper::saveTrackFile($data['track']);
            $duration = TrackHelper::getTrackDurationInSeconds($trackPath);

            $newTrack = new Track([
                'title' => $data['title'],
                'cover' => $imagePath,
                'path' => $trackPath,
                'genre_id' => $data['genre'],
                'explicit' => $data['explicit'],
                'album_id' => $data['album'] ?? null,
                'owner_id' => $data['owner'],
                'duration' => $duration
            ]);
            $newTrack->save();

            if (isset($data['features'])) {
                foreach ($data['features'] as $feature) {
                    $newTrack->features()->attach([
                        'artist_id' =>$feature
                    ]);
                }
            }
            DB::commit();

            return $this->success('Added new track', $trackPath, 201);

        }
        catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return $this->error('Unexpected error, try again later.', 500);
        }

    }

    function delete(string $id): JsonResponse
    {
        $trackToDelete = Track::query()->find($id);
        if (!$trackToDelete) return $this->error('Not found', 400);

        $trackToDelete->delete();
        TrackPlay::query()->where('track_id', '=', $id)->delete();
        return $this->success('Deleted track', null, 204);
    }

    public function update(array $data, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $track = Track::query()->findOrFail($id);

            if(isset($data['title'])) {
                $track->title = $data['title'];
            }

            if(isset($data['genre'])) {
                $track->genre_id = $data['genre'];
            }

            if(isset($data['explicit'])) {
                $track->explicit = (bool) $data['explicit'];
            }

            if(isset($data['album'])) {
                $track->album_id = $data['album'];
            }

            if(isset($data['owner'])) {
                $track->owner_id = $data['owner'];
            }

            if(isset($data['cover'])) {
                $imagePath = ImageHelper::uploadImage($data['cover'], 'tracks');
                $track->cover = $imagePath;
            }

            if(isset($data['track'])) {
                $trackPath = TrackHelper::saveTrackFile($data['track']);
                $duration = TrackHelper::getTrackDurationInSeconds($trackPath);

                $track->path = $trackPath;
                $track->duration = $duration;
            }


            $track->save();

            if (isset($data['features'])) {
                $track->features()->detach();

                foreach ($data['features'] as $feature) {
                    $track->features()->attach([
                        'artist_id' =>$feature
                    ]);
                }
            }
            DB::commit();

            return $this->success('Updated track', $track, 201);

        }
        catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return $this->error($exception->getMessage(), 500);
        }
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

    public function deleteMany(\Illuminate\Http\Request $request): JsonResponse
    {
        $ids = $request->get('data');

        try{
            Track::destroy($ids);
        }
        catch (\Exception $exception) {
            return $this->error('Error on deleting', 422);
        }
        return $this->success('Delete Many', []);
    }
}

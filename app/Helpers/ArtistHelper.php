<?php

namespace App\Helpers;

use App\Models\Artist;
use App\Models\Track;
use App\Models\TrackPlay;
use Carbon\Carbon;

class ArtistHelper
{
    public static function getMonthlyListeners(Artist $artist) : int {
        $now = Carbon::now();
        $time = $now->copy()->subMonth();

        $ownTrackIds = $artist->tracks()->pluck('id')->toArray();
        $featuresId = $artist->features()->pluck('id')->toArray();
        $tracks = array_merge($ownTrackIds, $featuresId);
        $tracks = Track::query()->whereHas('trackPlays')->whereIn('id', $tracks)->pluck('id');
        $uniqueMonthlyActors = TrackPlay::query()->whereIn('track_id', $tracks)->whereBetween('created_at', [$time, $now])->get()->pluck('user_id')->filter()->unique();
        return count($uniqueMonthlyActors);
    }
}

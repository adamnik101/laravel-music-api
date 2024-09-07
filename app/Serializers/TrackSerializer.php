<?php

namespace App\Serializers;

use App\Models\Track;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class TrackSerializer
{
    public static function serialize(Track $track) : array {
        return [
            "id" => $track->id,
            "title" => $track->title,
            "features" => $track->features,
            "liked_by" => $track->likedBy()
        ];
    }
}

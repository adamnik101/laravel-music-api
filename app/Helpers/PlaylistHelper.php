<?php

namespace App\Helpers;

use App\Models\Playlist;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlaylistHelper
{
    public static function deletePlaylist(Playlist $playlist): void
    {
        self::deleteTracksFromPlaylist($playlist);
        $playlist->delete();
    }
    public static function deleteTracksFromPlaylist(Playlist $playlist): int
    {
        return $playlist->tracks()->detach();
    }

    public static function addTracks(Playlist $playlist, array $tracks)
    {
        foreach ($tracks as $track) {
            $playlist->tracks()->attach($track, [
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
    }
}

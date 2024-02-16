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
}

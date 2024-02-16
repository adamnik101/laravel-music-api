<?php

namespace App\Helpers;

use App\Models\Album;
use App\Models\User;

class AlbumHelper
{
    public static function deleteUserLikedAlbums(User $user)
    {
        $user->likedAlbums()->detach();
    }

    public static function deleteAlbum(Album $album): bool
    {
        self::deleteAlbumTracks($album);
        self::deleteLikedBy($album);
        return $album->delete();
    }

    public static function deleteAlbumTracks(Album $album): int
    {
        return $album->tracks()->update(['album_id' => null]);
    }
    public static function deleteLikedBy(Album $album): int
    {
        return $album->likedBy()->detach();
    }
}

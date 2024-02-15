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
}

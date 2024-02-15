<?php

namespace App\Helpers;

use App\Models\User, App\Helpers\PlaylistHelper;
class UserHelper
{
    public static function deleteUserRelatedData(User $userToDelete): void
    {
        self::deletePlaylists($userToDelete);
        self::deleteLikedTracks($userToDelete);
        self::deleteLikedAlbums($userToDelete);
        self::deleteFollowings($userToDelete);
    }

    public static function deletePlaylists(User $user)
    {
        foreach ($user->playlists as $playlist) {
            $playlist->tracks()->detach();
            $playlist->delete();
        }
    }
    public static function deleteLikedTracks(User $user)
    {
        return $user->likedTracks()->detach();
    }

    public static function deleteLikedAlbums(User $user)
    {
        return $user->likedAlbums()->detach();
    }

    public static function deleteFollowings(User $user)
    {
        return $user->followings()->detach();
    }

}

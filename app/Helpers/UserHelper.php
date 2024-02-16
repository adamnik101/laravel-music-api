<?php

namespace App\Helpers;

use App\Models\User, App\Helpers\PlaylistHelper;
class UserHelper
{
    public static function deleteUserRelatedData(User $userToDelete): void
    {
        self::deleteSettings($userToDelete);
        self::deletePlaylists($userToDelete);
        self::deleteLikedTracks($userToDelete);
        self::deleteLikedAlbums($userToDelete);
        self::deleteFollowings($userToDelete);
        self::deleteUserTokens($userToDelete);
    }

    public static function deletePlaylists(User $user): void
    {
        foreach ($user->playlists as $playlist) {
            PlaylistHelper::deletePlaylist($playlist);
        }
    }
    public static function deleteLikedTracks(User $user): int
    {
        return $user->likedTracks()->detach();
    }

    public static function deleteLikedAlbums(User $user): int
    {
        return $user->likedAlbums()->detach();
    }

    public static function deleteFollowings(User $user): int
    {
        return $user->followings()->detach();
    }

    public static function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    public static function deleteSettings(User $user): int
    {
        return $user->settings()->delete();
    }

    public static function deleteUserTokens(User $user): int
    {
        return $user->tokens()->delete();
    }

}

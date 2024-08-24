<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    public static function uploadImage(UploadedFile $file, string $directory): string
    {
        $extension = $file->getClientOriginalExtension();
        $imageName = time(). '.' . $extension;
        $user = \auth()->user()->getAuthIdentifier();
        $file->move("F:\\new ng\\ng-zavrsni\public\images\users\\".$user."\\$directory", $imageName);
        return '/images/users/'.auth()->user()->getAuthIdentifier().'/'.$directory.'/'.$imageName;
    }
}

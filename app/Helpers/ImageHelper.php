<?php

namespace App\Helpers;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    public static function uploadImage(UploadedFile $file, string $directory): string
    {
        $name = $file->getClientOriginalName();
        $file->move("F:\\new ng\\ng-zavrsni\public\\", $name);
        return $name;
    }
}

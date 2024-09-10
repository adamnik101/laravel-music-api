<?php

namespace App\Helpers;

use App\Traits\ResponseAPI;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use wapmorgan\Mp3Info\Mp3Info;

class TrackHelper
{
    use ResponseAPI;
    public static function saveTrackFile(UploadedFile $file) : string
    {

        $extension = $file->getClientOriginalExtension();
        $newName = $file->getClientOriginalName();

        $file->move("F:\\new ng\\ng-zavrsni\public\\audio\\", $newName);

        return 'audio/'.$newName;
    }
    public static function getTrackDurationInSeconds(string $filePath): ?float
    {
        try {
            $info = new Mp3Info('F:\\new ng\\ng-zavrsni\public\\'.$filePath);
            return floor($info->duration);
        }
        catch (\Exception $exception) {
            Log::error('User tried getting track info duration | '. $exception->getMessage());
            return null;
        }
    }
}

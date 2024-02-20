<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\JsonResponse;

interface AlbumInterface extends BaseInterface
{
    public function newReleases() : JsonResponse;
    public function trending() : JsonResponse;
}

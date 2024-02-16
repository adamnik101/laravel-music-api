<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\JsonResponse;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    function fetchUserLikedTracks() : JsonResponse;
}

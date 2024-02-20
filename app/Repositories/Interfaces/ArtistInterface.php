<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\BaseInterface;
use Illuminate\Http\JsonResponse;

interface ArtistInterface extends BaseInterface
{
    public function trending() : JsonResponse;

}

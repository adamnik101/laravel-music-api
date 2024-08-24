<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\JsonResponse;

interface AdminInterface
{
    public function dashboard() : JsonResponse;
    public function artists() : JsonResponse;
    public function albums() : JsonResponse;
}

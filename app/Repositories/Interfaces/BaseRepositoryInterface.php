<?php

namespace App\Repositories\Interfaces;

use Illuminate\Http\JsonResponse;

interface BaseRepositoryInterface
{
    function fetchAll() : JsonResponse;
    function fetchOne(string $id) : JsonResponse;
    function delete() : JsonResponse;
    function update() : JsonResponse;
}

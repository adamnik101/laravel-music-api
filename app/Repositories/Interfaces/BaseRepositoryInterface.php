<?php

namespace App\Repositories\Interfaces;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

interface BaseRepositoryInterface
{
    function fetchAll() : JsonResponse;
    function fetchOne(string $id) : JsonResponse;

    function insert(FormRequest $request);
    function delete(string $id) : JsonResponse;
    function update(string $id) : JsonResponse;
}

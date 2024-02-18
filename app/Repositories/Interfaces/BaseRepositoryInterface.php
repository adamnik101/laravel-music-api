<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\InsertTracksToPlaylistRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

interface BaseRepositoryInterface
{
    function fetchAll() : JsonResponse;
    function fetchOne(string $id) : JsonResponse;

    function insert(array $data);
    function delete(string $id) : JsonResponse;
    function update(array $data, string $id) : JsonResponse;
}

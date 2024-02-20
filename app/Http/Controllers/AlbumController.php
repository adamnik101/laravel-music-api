<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Repositories\Interfaces\AlbumInterface;
use App\Repositories\Interfaces\BaseInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlbumController extends BaseController {

    public function __construct(AlbumInterface $repository)
    {
        parent::__construct($repository);
    }
    public function insert(AlbumRequest $request)
    {
        return $this->repository->insert($request->validated());
    }
    public function update(AlbumRequest $request, string $id): JsonResponse
    {
        return $this->repository->update($request->validated(), $id);
    }
    public function newReleases() : JsonResponse
    {
        return $this->repository->newReleases();
    }
    public function trending() : JsonResponse
    {
        return $this->repository->trending();
    }
}

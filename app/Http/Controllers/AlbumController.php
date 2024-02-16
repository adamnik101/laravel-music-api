<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AlbumController extends BaseController {

    public function __construct(AlbumRepositoryInterface $repository)
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
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Repositories\Interfaces\ArtistRepositoryInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistController extends BaseController
{
    public function __construct(ArtistRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function insert(ArtistRequest $request)
    {
        return $this->repository->insert($request->validated());
    }
    public function update(ArtistRequest $request, string $id): JsonResponse
    {
        return $this->repository->update($request->validated(), $id);
    }
}

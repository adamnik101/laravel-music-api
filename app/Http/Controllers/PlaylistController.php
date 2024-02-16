<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistRequest;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\PlaylistRepositoryInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlaylistController extends BaseController
{
    public function __construct(PlaylistRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function insert(PlaylistRequest $request)
    {
        return $this->repository->insert($request->validated());
    }

    public function update(PlaylistRequest $request, string $id): JsonResponse
    {
        return $this->repository->update($request->validated(), $id);
    }
}

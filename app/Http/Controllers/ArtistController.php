<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Http\Requests\ManyUuidsRequest;
use App\Repositories\Interfaces\ArtistInterface;
use App\Repositories\Interfaces\BaseInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArtistController extends BaseController
{
    public function __construct(ArtistInterface $repository)
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
    public function trending() : JsonResponse
    {
        return $this->repository->trending();
    }
    public function deleteMany(ManyUuidsRequest $request): JsonResponse
    {
        return $this->repository->deleteMany($request);
    }
}

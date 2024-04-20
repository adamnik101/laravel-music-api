<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Repositories\Interfaces\BaseInterface;
use App\Repositories\Interfaces\GenreInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends BaseController
{
    public function __construct(GenreInterface $repository)
    {
        parent::__construct($repository);
    }

    public function insert(GenreRequest $request)
    {
        return $this->repository->insert(array($request->validated()));
    }
    public function update(GenreRequest $request, string $id): JsonResponse
    {
        return $this->repository->update($request->validated(), $id);
    }
}

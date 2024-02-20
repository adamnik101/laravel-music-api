<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackRequest;
use App\Repositories\Interfaces\TrackInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackController extends BaseController {

    public function __construct(TrackInterface $trackRepository) {
        parent::__construct($trackRepository);
    }

    public function insert(TrackRequest $request)
    {
        return $this->repository->insert($request->validated());
    }

    public function update(TrackRequest $request, string $id): JsonResponse
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

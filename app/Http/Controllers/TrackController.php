<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackRequest;
use App\Repositories\Interfaces\TrackRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrackController extends BaseController {

    public function __construct(TrackRepositoryInterface $trackRepository) {
        parent::__construct($trackRepository);
    }

    public function insert(TrackRequest $request)
    {
        return $this->repository->insert($request->validated());
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistRequest;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\PlaylistRepositoryInterface;
use Illuminate\Http\Request;

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
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumRequest;
use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class AlbumController extends BaseController {

    public function __construct(AlbumRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
    public function insert(AlbumRequest $request)
    {
        return $this->repository->insert($request->validated());
    }

}

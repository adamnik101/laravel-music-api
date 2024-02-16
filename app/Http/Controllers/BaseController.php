<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistRequest;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected BaseRepositoryInterface $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function fetchAll() : JsonResponse
    {
        return $this->repository->fetchAll();
    }
    public function fetchOne(string $id) : JsonResponse
    {
        return $this->repository->fetchOne($id);
    }
    public function insert(FormRequest $request)
    {
        return $this->repository->insert($request);
    }
    public function update(string $id) : JsonResponse
    {
        return $this->repository->update($id);
    }
    public function delete(string $id) : JsonResponse
    {
        return $this->repository->delete($id);
    }
}

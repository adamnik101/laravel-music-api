<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BaseRepositoryInterface;
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
    public function update(string $id) : JsonResponse
    {
        return $this->repository->update($id);
    }
    public function delete(string $id) : JsonResponse
    {
        return $this->repository->delete($id);
    }
}

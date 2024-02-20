<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaylistRequest;
use App\Repositories\Interfaces\BaseInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected BaseInterface $repository;

    public function __construct(BaseInterface $repository)
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
    public function delete(string $id) : JsonResponse
    {
        return $this->repository->delete($id);
    }
}

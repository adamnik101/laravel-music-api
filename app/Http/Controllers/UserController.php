<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController {

    public function __construct(UserRepositoryInterface $userRepository) {
        parent::__construct($userRepository);
    }

    public function insert(UserRequest $request)
    {
        return $this->repository->insert($request->validated());
    }
}

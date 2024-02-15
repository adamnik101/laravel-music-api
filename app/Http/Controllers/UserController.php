<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends BaseController {

    public function __construct(UserRepositoryInterface $userRepository) {
        parent::__construct($userRepository);
    }
}

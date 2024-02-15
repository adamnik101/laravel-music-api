<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;

interface AuthRepositoryInterface
{
    function login(LoginRequest $request);
    function register(RegisterRequest $request);
}

<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;

interface AuthInterface
{
    function login(LoginRequest $request);
    function register(RegisterRequest $request);
    function getToken() : JsonResponse;
    function getUser() : JsonResponse;
    function logout() : JsonResponse;
}

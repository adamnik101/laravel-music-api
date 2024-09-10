<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Interfaces\AuthInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthInterface $authRepository;

    function __construct(AuthInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    function login(LoginRequest $request)
    {
        return $this->authRepository->login($request);
    }

    function register(RegisterRequest $request)
    {
        return $this->authRepository->register($request);
    }
    function getToken() : JsonResponse
    {
        return $this->authRepository->getToken();
    }

    function getUser() : JsonResponse
    {
        return $this->authRepository->getUser();
    }
    function logout() : JsonResponse
    {
        return $this->authRepository->logout();
    }

    function fetchUserRole(): JsonResponse
    {
        return $this->authRepository->fetchUserRole();
    }
}

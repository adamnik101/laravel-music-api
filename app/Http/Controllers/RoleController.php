<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\RoleInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private RoleInterface $role;

    public function __construct(RoleInterface $role)
    {
        $this->role = $role;
    }
    public function fetchAll() : JsonResponse
    {
        return $this->role->fetchAll();
    }
}

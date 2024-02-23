<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AdminInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected AdminInterface $admin;
    public function __construct(AdminInterface $admin)
    {
        $this->admin = $admin;
    }

    public function dashboard() : JsonResponse
    {
        return $this->admin->dashboard();
    }
}

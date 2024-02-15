<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends BaseController
{
    public function __construct(GenreRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}

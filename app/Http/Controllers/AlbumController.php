<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\AlbumRepositoryInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\Request;

class AlbumController extends BaseController {

    public function __construct(AlbumRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

}

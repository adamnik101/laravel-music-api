<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\PlaylistRepositoryInterface;
use Illuminate\Http\Request;

class PlaylistController extends BaseController
{
    public function __construct(PlaylistRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

}

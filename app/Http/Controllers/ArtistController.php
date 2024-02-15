<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ArtistRepositoryInterface;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\Request;

class ArtistController extends BaseController
{
    public function __construct(ArtistRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }
}

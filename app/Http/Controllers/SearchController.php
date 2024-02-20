<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\SearchInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private SearchInterface $repository;
    public function __construct(SearchInterface $repository)
    {
        $this->repository = $repository;
    }

    public function search(Request $request) : JsonResponse
    {
        return $this->repository->search($request->query('query', ''));
    }
    public function searchTracks(Request $request) : JsonResponse
    {
        return $this->repository->searchTracks($request->all());
    }
    public function searchAlbums(Request $request) : JsonResponse
    {
        return $this->repository->searchAlbums($request->all());
    }
    public function searchArtists(Request $request) : JsonResponse
    {
        return $this->repository->searchArtists($request->all());
    }
}

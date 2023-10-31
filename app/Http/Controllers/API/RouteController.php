<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Repositories\Route\RouteRepositoryInterface;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    private $routeRepository;

    public function __construct(RouteRepositoryInterface $routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function getStartLocations()
    {
        $startLocations = $this->routeRepository->getStartLocation();
        return HttpResponse::respondWithSuccess($startLocations);
    }

    public function getEndLocations(Request $request)
    {
        $endLocations = $this->routeRepository->getEndLocation($request->start_location);
        return HttpResponse::respondWithSuccess($endLocations);
    }
}

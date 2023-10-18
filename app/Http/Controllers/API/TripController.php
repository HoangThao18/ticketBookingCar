<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\HttpResponse;
use App\Http\Resources\TripResource;
use App\Repositories\Route\RouteRepositoryInterface;
use App\Repositories\Trip\TripRepositoryInterface;
use Illuminate\Http\Request;

class TripController extends Controller
{
    private $tripRepository;
    private $routeRepository;

    public function __construct(TripRepositoryInterface $tripRepository, RouteRepositoryInterface $routeRepository)
    {
        $this->tripRepository = $tripRepository;
        $this->routeRepository = $routeRepository;
    }

    public function show($id)
    {
        $trip = $this->tripRepository->find($id);
        $tripResource = new TripResource($trip);
        return HttpResponse::respondWithSuccess($tripResource);
    }

    public function search(Request $request)
    {
        $route = $this->routeRepository->searchByLocation($request->start_location, $request->end_location);
        $trip = $this->tripRepository->findByRoute($route->id);
        return HttpResponse::respondWithSuccess(TripResource::collection($trip));
    }
}

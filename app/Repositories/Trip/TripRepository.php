<?php

namespace App\Repositories\Trip;

use App\Repositories\BaseRepository;
use App\Repositories\Route\RouteRepository;

class TripRepository extends BaseRepository implements TripRepositoryInterface
{
  protected $routeRepository;

  public function getModel()
  {
    return \App\Models\Trip::class;
  }

  public function searchByRoute($startLocation, $endLocation)
  {
    $routeRepository = new RouteRepository;
    $route = $routeRepository->searchByLocation($startLocation, $endLocation);
    dd($route);
  }
}

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

  public function findByRoute($routeId)
  {
    return $this->model::where('route_id', $routeId)->paginate(10);
  }
}

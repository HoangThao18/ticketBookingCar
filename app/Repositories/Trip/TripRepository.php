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

  public function findByRoute($routeId, $time)
  {
    return $this->model::where('route_id', $routeId)
      ->where('departure_time', $time)
      ->where("status", "scheduled")
      ->paginate(10);
  }

  public function getTrips()
  {
    return $this->model->orderBy('departure_time')->paginate(10);
  }
}

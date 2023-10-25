<?php

namespace App\Repositories\Trip;

use App\Repositories\BaseRepository;
use App\Repositories\Route\RouteRepository;
use Illuminate\Support\Facades\DB;

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
      ->where("status", "chờ khởi hành")
      ->get();
  }

  public function getTrips()
  {
    return $this->model->with('car', 'route', "driver")->orderBy('departure_time')->paginate(10);
  }

  public function find($id)
  {
    return $this->model->with('car', 'route', "driver", 'tickets', "tickets.seat")->firstOrFail();
  }

  public function getPopularTrips()
  {
    return $this->model::select('route_id', DB::raw('COUNT(*) as trip_count'))
      ->groupBy('route_id')
      ->with('route')
      ->orderByDesc('trip_count')
      ->limit(6)
      ->get();
  }
}

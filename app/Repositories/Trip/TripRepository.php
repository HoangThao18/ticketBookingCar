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

  public function getPopularTrips($location)
  {
    return DB::table('trips')
      ->join('routes', "trips.route_id", '=', "routes.id")
      ->select('routes.start_location', "routes.end_location", DB::raw('COUNT(*) as trip_count'))
      ->where('routes.start_location', $location)
      ->groupBy('routes.start_location',)
      ->groupBy('routes.end_location',)
      ->orderByDesc('trip_count')
      ->limit(3)
      ->get();
  }
}

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

  public function getByRoute($startStaionIds, $endStationIds)
  {
    return $this->model::whereIn('start_station', $startStaionIds)
      ->WhereIn('end_station', $endStationIds)
      ->Where("status", "chờ khởi hành")
      ->get();
  }

  public function getTrips()
  {
    return $this->model->with('car', 'route', "driver")->orderBy('departure_time')->paginate(10);
  }

  public function find($id)
  {
    return $this->model->with('car', 'start', "driver", 'time_points', 'time_points.point', 'end', 'tickets', "tickets.seat")->firstOrFail();
  }

  public function getPopularTrips()
  {
    return $this->model
      ->with('start', 'end', 'car')
      ->select('trips.start_station', "trips.end_station", DB::raw('COUNT(*) as trip_count'))
      ->groupBy('trips.start_station',)
      ->groupBy('trips.end_station',)
      ->orderByDesc('trip_count')
      ->limit(3)
      ->get();
  }
}

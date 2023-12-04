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

  public function getByRoute($startStaionIds, $endStationIds, $date)
  {
    return $this->model::whereIn('start_station', $startStaionIds)
      ->WhereIn('end_station', $endStationIds)
      ->whereDate('departure_time', $date)
      ->Where("status", "chờ khởi hành")
      ->get();
  }

  public function getTrips()
  {
    return $this->model->with('car', "driver")->orderByDesc('departure_time')->get();
  }

  public function find($id)
  {
    return $this->model->with('car', 'start', "driver", 'time_points', 'time_points.point', 'end', 'tickets', "tickets.seat")->findOrFail($id);
  }

  public function getByCarAndDepart($car_id, $departure_time)
  {
    return $this->model->where("car_id", $car_id)
      ->where("departure_time", "<=", $departure_time)->where('arrival_time', ">=", $departure_time)->first();
  }

  public function findNotAssociateColumn($id)
  {
    return $this->model->findOrFail($id);
  }

  public function getPopularTrips()
  {
    return  DB::table('trips')
      ->join('cars', 'trips.car_id', '=', 'cars.id')
      ->join('seats', 'cars.id', '=', 'seats.car_id')
      ->join('stations as start_station', 'trips.start_station', '=', 'start_station.id')
      ->join('stations as end_station', 'trips.end_station', '=', 'end_station.id')
      ->select('start_station.province as start_station', 'end_station.province as end_station', DB::raw('MIN(seats.price) as min_price'))
      ->orderByDesc(DB::raw('COUNT(trips.id)'))
      ->groupBy('start_station.province', 'end_station.province')
      ->limit(6)
      ->get();
  }

  public function changeStatus($id, $status)
  {
    $trip = $this->model->findOrFail($id);
    if ($trip) {
      $trip->update(['status' => $status]);
      return true;
    }
    return false;
  }
}

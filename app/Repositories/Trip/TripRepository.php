<?php

namespace App\Repositories\Trip;

use App\Http\Library\HttpResponse;
use App\Http\Resources\Trip\TripResource;
use App\Repositories\BaseRepository;
use App\Repositories\Route\RouteRepository;
use Illuminate\Support\Carbon;
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

  public function checkCanCancelTicket($id, $date)
  {
    $trip = $this->find($id);

    if ($date <= $trip->departure_time) {
      return true;
    }
    return false;
  }


  public function findTripByDriver($driver_id)
  {
    return $this->model->where('driver_id', '=', $driver_id)->orderBy("departure_time", "DESC")->get();
  }

  public function getByStatus($status = null, $time = null)
  {
    $query = $this->model->select('*');
    // Filter by status
    if (!is_null($status)) {
      $query->whereIn('status', (array)$status);
    }
    if (!is_null($time)) {
      // $fromTime
      // $toTime
      // Filter by time
      // dd($time);
      $query->whereDate('departure_time', $time);


      // $dates = explode("/", $time);
      // $year = $dates[0];
      // $month = $dates[1];
      // $day = $dates[2];
      // $checkDay = $query->get();
      // $dayList = [];
      // foreach ($checkDay as $item) {
      //   // Chuyển đổi chuỗi thành đối tượng Carbon
      //   $carbonDate = Carbon::parse($item->arrival_time);

      //   // Lấy ngày tháng năm dưới định dạng 'Y-m-d'
      //   $dateFormatted = $carbonDate->format('Y-m-d');
      //   $dayList[] = $dateFormatted;
      // };
      // if (!in_array("$year-$month-$day", $dayList)) {
      //   return null;
      // }

      // $fromTime = date("Y-m-d H:i:s", strtotime("$year-$month-$day 0:00"));
      // $toTime = date("Y-m-d H:i:s", strtotime("$year-$month-" . ($day + 1) . " 23:59:59"));
      // $query->whereBetween('arrival_time', [$fromTime, $toTime]);
    }
    return $query->get();
  }
}

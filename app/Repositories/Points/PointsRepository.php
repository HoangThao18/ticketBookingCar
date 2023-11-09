<?php

namespace App\Repositories\Points;

use App\Repositories\BaseRepository;

class PointsRepository extends BaseRepository implements PointsRepositoryInterface
{
  protected $routeRepository;

  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Point::class;
  }

  public function searchByLocation($startLocation, $endLocation)
  {
    return $this->model::where('start_location', $startLocation)->where('end_location', $endLocation)->firstOrFail();
  }

  public function getStartLocation()
  {
    return $this->model::select('start_location')->GroupBy("start_location")->get();
  }

  public function getEndLocation($startLocation)
  {
    return $this->model::select('end_location')->where("start_location", $startLocation)->get();
  }
}

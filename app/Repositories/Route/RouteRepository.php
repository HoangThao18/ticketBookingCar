<?php

namespace App\Repositories\Route;

use App\Repositories\BaseRepository;

class RouteRepository extends BaseRepository implements RouteRepositoryInterface
{
  protected $routeRepository;

  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Route::class;
  }

  public function searchByLocation($startLocation, $endLocation)
  {
    return $this->model::where('start_location', $startLocation)->where('end_location', $endLocation)->findOrFail();
  }
}

<?php

namespace App\Repositories\Trip;

use App\Repositories\BaseRepository;

class TripRepository extends BaseRepository implements TripRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Trip::class;
  }

  public function getTrip()
  {
    return $this->model::all()->paginate(10);
  }
}

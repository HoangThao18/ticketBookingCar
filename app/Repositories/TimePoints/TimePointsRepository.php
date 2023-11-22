<?php

namespace App\Repositories\TimePoints;

use App\Models\TimePoints;
use App\Repositories\BaseRepository;

class TimePointsRepository extends BaseRepository implements TimePointsRepositoryInterface
{
  public function getModel()
  {
    return TimePoints::class;
  }

  public function deleteByTrip($trip_id)
  {
    $this->model->where('trip_id', $trip_id)->delete();
  }
}

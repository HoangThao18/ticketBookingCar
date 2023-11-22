<?php

namespace App\Repositories\TimePoints;

use App\Repositories\BaseRepositoryInterface;

interface TimePointsRepositoryInterface extends BaseRepositoryInterface
{
  public function deleteByTrip($trip_id);
}

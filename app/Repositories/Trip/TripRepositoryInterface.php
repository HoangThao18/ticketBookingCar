<?php

namespace App\Repositories\Trip;

use App\Repositories\BaseRepositoryInterface;

interface TripRepositoryInterface extends BaseRepositoryInterface
{
  public function findByRoute($routeId, $time);

  public function getTrips();

  public function find($id);

  public function getPopularTrips();
}

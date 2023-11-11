<?php

namespace App\Repositories\Trip;

use App\Repositories\BaseRepositoryInterface;

interface TripRepositoryInterface extends BaseRepositoryInterface
{
  public function getByRoute($startStationIds, $endStationIds, $date);

  public function getTrips();

  public function find($id);

  public function getPopularTrips();

  public function findNotAssociateColumn($id);
}

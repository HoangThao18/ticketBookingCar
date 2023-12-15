<?php

namespace App\Repositories\Trip;

use App\Repositories\BaseRepositoryInterface;

interface TripRepositoryInterface extends BaseRepositoryInterface
{
  public function getByRoute($startStationIds, $endStationIds, $date);

  public function getTrips();

  public function getByCarAndDepart($car_id, $departure_time);

  public function find($id);

  public function getPopularTrips();

  public function findNotAssociateColumn($id);

  public function changeStatus($id, $status);

  public function checkCanCancelTicket($id, $date);

  public function getByDriver($driverId);
}

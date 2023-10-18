<?php

namespace App\Repositories\Trip;

use App\Repositories\BaseRepositoryInterface;

interface TripRepositoryInterface extends BaseRepositoryInterface
{
  public function findByRoute($routeId);
}

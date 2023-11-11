<?php

namespace App\Repositories\Seats;

use App\Repositories\BaseRepositoryInterface;

interface SeatsRepositoryInterface extends BaseRepositoryInterface
{
  public function getByCar($id);
}

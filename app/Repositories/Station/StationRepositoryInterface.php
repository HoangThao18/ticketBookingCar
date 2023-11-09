<?php

namespace App\Repositories\Station;

use App\Repositories\BaseRepositoryInterface;

interface StationRepositoryInterface extends BaseRepositoryInterface
{
  public function getByProvince($province);
}

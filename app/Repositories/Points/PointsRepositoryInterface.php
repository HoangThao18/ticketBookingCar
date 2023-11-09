<?php

namespace App\Repositories\Points;

use App\Repositories\BaseRepositoryInterface;

interface PointsRepositoryInterface extends BaseRepositoryInterface
{
  function searchByLocation($startLocation, $endLocation);

  function getStartLocation();

  function getEndLocation($startLocation);
}

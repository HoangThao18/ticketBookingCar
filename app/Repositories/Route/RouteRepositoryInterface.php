<?php

namespace App\Repositories\Route;

use App\Repositories\BaseRepositoryInterface;

interface RouteRepositoryInterface extends BaseRepositoryInterface
{
  function searchByLocation($startLocation, $endLocation);

  function getStartLocation();

  function getEndLocation($startLocation);
}

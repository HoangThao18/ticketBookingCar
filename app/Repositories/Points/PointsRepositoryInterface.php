<?php

namespace App\Repositories\Points;

use App\Repositories\BaseRepositoryInterface;

interface PointsRepositoryInterface extends BaseRepositoryInterface
{
  public function getByStation($id);
}

<?php

namespace App\Repositories\Car;

use App\Repositories\BaseRepositoryInterface;

interface CarRepositoryInterface extends BaseRepositoryInterface
{
  public function increaseNumberSeat($id);

  public function decreaseNumberSeat($id);
}

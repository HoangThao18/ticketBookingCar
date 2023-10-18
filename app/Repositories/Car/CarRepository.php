<?php

namespace App\Repositories\Car;

use App\Repositories\BaseRepository;

class CarRepository extends BaseRepository implements CarRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Car::class;
  }
}

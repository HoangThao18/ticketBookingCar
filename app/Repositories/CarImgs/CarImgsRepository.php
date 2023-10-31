<?php

namespace App\Repositories\CarImgs;

use App\Repositories\BaseRepository;
use App\Repositories\CarImgs\CarImgsRepositoryInterface;

class CarImgsRepository extends BaseRepository implements CarImgsRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Car_Imgs::class;
  }
}

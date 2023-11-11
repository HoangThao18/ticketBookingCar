<?php

namespace App\Repositories\Seats;

use App\Repositories\BaseRepository;

class SeatsRepository extends BaseRepository implements SeatsRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Seat::class;
  }

  public function getByCar($id)
  {
    return $this->model->where('car_id', $id)->get();
  }
}

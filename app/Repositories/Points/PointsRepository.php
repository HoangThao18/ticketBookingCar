<?php

namespace App\Repositories\Points;

use App\Repositories\BaseRepository;

class PointsRepository extends BaseRepository implements PointsRepositoryInterface
{

  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Points::class;
  }

  public function getByStation($id)
  {
    return $this->model->where('station_id', $id)->get();
  }
}

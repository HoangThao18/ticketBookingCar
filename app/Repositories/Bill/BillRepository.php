<?php

namespace App\Repositories\Bill;

use App\Repositories\BaseRepository;

class BillRepository extends BaseRepository implements BillRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Bill::class;
  }

  public function findByCode($code)
  {
    return $this->model->where('code', $code)->firstOrFail();
  }
}

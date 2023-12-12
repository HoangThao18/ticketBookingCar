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

  // getAll
  public function getAll()
  {
    return $this->model->all();
  }

  // findByStatus
  public function findByStatus($status = null, $time = null)
  {
    if ($status == 'waiting') {
      $query = $this->model->whereIn('status', ['pending', 'processing']);
    } elseif ($status == 'cancelled') {
      $query = $this->model->where('status', 'cancelled');
    } else {
      $query = $this->model->where('status', $status);
    }

    if (isset($time)) {
      $timeThreshold = now()->subMinutes($time)->toDateTimeString();
      // Thêm điều kiện cho thời gian
      $query->where('created_at', '<', $timeThreshold);
    }

    return $query->get();
  }
}

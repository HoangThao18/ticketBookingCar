<?php

namespace App\Repositories\BillDetail;

use App\Repositories\BaseRepository;

class BillDetailRepository extends BaseRepository implements BillDetailRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Bill::class;
  }
  public function getTicketIdsByBill($id)
  {
    return $this->model->select('ticket_id')->where('bill_id', $id)->get();
  }
}

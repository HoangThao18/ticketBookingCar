<?php

namespace App\Repositories\BillDetail;

use App\Repositories\BaseRepositoryInterface;

interface BillDetailRepositoryInterface extends BaseRepositoryInterface
{
  public function getTicketIdsByBill($id);
}

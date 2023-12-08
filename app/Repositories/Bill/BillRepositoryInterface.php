<?php

namespace App\Repositories\Bill;

use App\Repositories\BaseRepositoryInterface;

interface BillRepositoryInterface extends BaseRepositoryInterface
{
  public function findByCode($code);
  // findByStatus
  public function findByStatus($status,$time = null);

}

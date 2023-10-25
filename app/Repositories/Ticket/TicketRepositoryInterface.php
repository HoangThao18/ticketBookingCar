<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepositoryInterface;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
  public function searchByCode($code);

  public function updateStatus($id, $status);
}

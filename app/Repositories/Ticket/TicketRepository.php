<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepository;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Ticket::class;
  }

  public function searchByCode($code)
  {
    return $this->model::where('code', $code)->first();
  }


  public function updateStatus($ticketId, $status)
  {
    $this->model->whereIn('id', $ticketId)->update(['status' => $status]);
  }
}

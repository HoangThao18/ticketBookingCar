<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Ticket::class;
  }

  public function searchByTripAndSeat($trip_id, $seat_id, $status = null)
  {
    $query = $this->model->where('trip_id', $trip_id)->where('seat_id', $seat_id);

    if ($status == 'waiting') {
      return $query->whereIn('status', ['pending', 'processing'])->first();
    } else {
      return $query->first();
    }
  }

  public function searchByCode($code, $phoneNumber)
  {
    return $this->model::where('code', $code)->first();
  }

  public function createMany($attributes = [])
  {
    return $this->model->createMany($attributes);
  }

  public function updateStatus($ticketId, $status)
  {
    $this->model->whereIn('id', $ticketId)->update(['status' => $status]);
  }

  public function getByTrip($id)
  {
    return $this->model->select('seat_id', 'status')
      ->where('trip_id', $id)
      ->where('status', 'booked')
      ->orwhere('status', "pending")
      ->get();
  }

  public function getByBill($id)
  {
    return $this->model->where('bill_id', $id)->get();
  }
}

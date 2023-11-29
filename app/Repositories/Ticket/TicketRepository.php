<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class TicketRepository extends BaseRepository implements TicketRepositoryInterface
{
  public function getModel()
  {
    return \App\Models\Ticket::class;
  }

  public function searchByCode($code, $phoneNumber)
  {
    return $this->model::with('user')->where('code', $code)->whereHas('user', function ($query) use ($phoneNumber) {
      $query->where('phone_number', $phoneNumber);
    })->first();
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

  public function CountSoldTickets($trip_id)
  {

    return DB::table('tickets')
      ->where('trip_id', $trip_id)
      ->whereIn('status', ['booked', 'pending'])
      ->count();
  }

  public function getAll()
  {
    return $this->model->with('trip', 'user', 'seat')->get();
  }

  public function find($id)
  {
    return $this->model->with('trip', "trip.car", 'user', 'seat')->findOrFail($id);
  }

  public function getHistoryBooked($id)
  {
    return $this->model->with('trip', 'trip.car', 'user', 'seat')->where('user_id', $id)->get();
  }
}

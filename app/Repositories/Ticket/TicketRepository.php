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
    $this->model->where('id', $ticketId)->update(['status' => $status]);
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

  public function getDailyTicketBooked()
  {
    return $this->model::whereDate('created_at', today())->where('status', "booked")->count();
  }

  public function getWeeklyTicketBooked()
  {
    return $this->model::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', "booked")
      ->count();
  }

  public function getTicketsBookedIn12()
  {

    $sixMonthsAgo = now()->subMonths(6);
    return $this->model::select(DB::raw('YEAR(created_at) as year, MONTH(created_at) as month'), DB::raw('COUNT(*) as total'))
      ->where('created_at', '>=', $sixMonthsAgo)
      ->where('created_at', '<=', now())
      ->where('status', "booked")
      ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
      ->orderBy('year', 'asc')
      ->orderBy('month', 'asc')
      ->get();
  }

  public function getMonthlyTicketBooked()
  {
    return $this->model::whereMonth('created_at', now()->month)->where('status', "booked")->count();
  }

  public function getDailySales()
  {
    return   intval($this->model::whereDate('created_at', today())->sum('price'));
  }

  public function getMonthlySales()
  {
    return intval($this->model::whereMonth('created_at', now()->month)->where('status', "booked")->sum('price'));
  }

  public function getWeeklySales()
  {
    return intval($this->model::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->where('status', "booked")
      ->sum('price'));
  }
}

<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepositoryInterface;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
  public function searchByCode($code);

  public function updateStatus($id, $status);

  public function getByTrip($id);

  public function createMany($artribute);

  public function getByBill($id);

  public function searchByTripAndSeat($trip_id, $seat_id);
}

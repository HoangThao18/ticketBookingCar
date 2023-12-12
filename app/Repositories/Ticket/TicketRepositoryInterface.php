<?php

namespace App\Repositories\Ticket;

use App\Repositories\BaseRepositoryInterface;

interface TicketRepositoryInterface extends BaseRepositoryInterface
{
  public function getAll();

  public function find($id);

  public function searchByCode($code, $phoneNumber);
  
  public function searchByTripAndSeat($trip_id, $seat_id, $status = null);

  public function updateStatus($id, $status);

  public function getByTrip($id);

  public function createMany($artribute);

  public function getByBill($id);
}

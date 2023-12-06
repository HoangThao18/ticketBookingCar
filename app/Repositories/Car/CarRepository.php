<?php

namespace App\Repositories\Car;

use App\Repositories\BaseRepository;

class CarRepository extends BaseRepository implements CarRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Car::class;
  }

  public function increaseNumberSeat($id)
  {
    $car = $this->model->findOrFail($id);

    if ($car) {
      $car->update(['number_seat' => $car->number_seat + 1]);
      return $car;
    }
    return false;
  }

  public function decreaseNumberSeat($id)
  {
    $car = $this->model->findOrFail($id);

    if ($car) {
      $car->update(['number_seat' => $car->number_seat - 1]);
      return $car;
    }
    return false;
  }

  public function getTotalCar()
  {
    return $this->model::count();
  }
}

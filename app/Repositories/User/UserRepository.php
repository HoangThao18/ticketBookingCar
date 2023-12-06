<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\Route\RouteRepository;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
  protected $userRepository;

  public function getModel()
  {
    return \App\Models\User::class;
  }

  public function changePassword($user, $password)
  {
    $user->update(['password' => $password]);
  }

  public function getUser()
  {
    return $this->model->where("role", 'user')->get();
  }

  public function getDriver()
  {
    return $this->model->where("role", 'driver')->get();
  }

  public function getByEmail($email)
  {
    return $this->model->where("email", $email)->first();
  }

  public function getByPhone($phone)
  {
    return $this->model->where("phone_number", $phone)->first();
  }

  public function getTotalDriver()
  {
    return $this->model->where('role', "driver")->count();
  }
}

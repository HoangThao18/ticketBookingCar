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
}

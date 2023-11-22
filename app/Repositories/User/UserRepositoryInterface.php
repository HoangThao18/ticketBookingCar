<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
  public function changePassword($user, $newPassword);

  public function getUser();

  public function getDriver();

  public function getByEmail($email);

  public function getByPhone($phone);
}

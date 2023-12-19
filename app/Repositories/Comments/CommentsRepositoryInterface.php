<?php

namespace App\Repositories\Comments;

use App\Repositories\BaseRepositoryInterface;

interface CommentsRepositoryInterface extends BaseRepositoryInterface
{
  public function getCommentsByCarId($carId);

  public function getTotalComments();
}

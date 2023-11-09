<?php

namespace App\Repositories\Comment;

use App\Repositories\BaseRepositoryInterface;

interface CommentRepositoryInterface extends BaseRepositoryInterface
{
  public function getCommentsByCarId($carId);
}

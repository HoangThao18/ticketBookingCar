<?php

namespace App\Repositories\Comment;

use App\Repositories\BaseRepository;

class CommentRepository extends BaseRepository implements CommentRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Comment::class;
  }

  public function getCommentsByCarId($carId)
  {
    return $this->model->where('car_id', $carId)->get();
  }
}

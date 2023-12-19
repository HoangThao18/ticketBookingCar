<?php

namespace App\Repositories\Comments;

use App\Repositories\BaseRepository;

class CommentsRepository extends BaseRepository implements CommentsRepositoryInterface
{
  //lấy model tương ứng
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\Comment::class;
  }

  public function getCommentsByCarId($carId)
  {
    return $this->model->where('car_id', $carId)->get();
  }

  public function getTotalComments()
  {
    return $this->model::count();
  }
}

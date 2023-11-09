<?php

namespace App\Repositories\News;

use App\Repositories\BaseRepository;

class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\News::class;
  }

  public function getLatestNews()
  {
    return $this->model->orderByDesc('created_at')->limit(5)->get();
  }

  public function getPopularNews()
  {
    return $this->model->orderByDesc("view")->limit(5)->get();
  }

  public function increaseView($new)
  {
    $new->update(['view' => $new->view + 1]);
  }
}

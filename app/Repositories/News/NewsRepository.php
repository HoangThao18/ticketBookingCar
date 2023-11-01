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
}

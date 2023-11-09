<?php

namespace App\Repositories\News;

use App\Repositories\BaseRepositoryInterface;

interface NewsRepositoryInterface extends BaseRepositoryInterface
{
  public function getPopularNews();

  public function getLatestNews();

  public function increaseView($new);
}

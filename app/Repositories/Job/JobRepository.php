<?php

namespace App\Repositories\Job;

use App\Repositories\BaseRepository;

class JobRepository extends BaseRepository implements JobRepositoryInterface
{
  //lấy model tương ứng
  public function getModel()
  {
    return \App\Models\job_opening::class;
  }
}

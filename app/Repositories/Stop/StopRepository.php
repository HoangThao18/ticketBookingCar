<?php

namespace App\Repositories\Stop;

use App\Models\Stop;
use App\Repositories\BaseRepository;

class StopRepository extends BaseRepository implements StopRepositoryInterface
{
  public function getModel()
  {
    return Stop::class;
  }
}

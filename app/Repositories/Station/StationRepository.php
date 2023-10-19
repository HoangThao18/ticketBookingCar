<?php

namespace App\Repositories\Station;

use App\Models\Station;
use App\Repositories\BaseRepository;

class StationRepository extends BaseRepository implements StationRepositoryInterface
{
  public function getModel()
  {
    return Station::class;
  }
}

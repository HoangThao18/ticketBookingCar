<?php

namespace App\Repositories\Station;

use App\Models\Station;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class StationRepository extends BaseRepository implements StationRepositoryInterface
{
  public function getModel()
  {
    return Station::class;
  }

  public function getByProvince($province)
  {
    return DB::table('stations')->where('province', $province)->pluck('id');
  }
}

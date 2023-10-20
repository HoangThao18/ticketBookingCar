<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = ['start_location', "end_location"];

    public function station()
    {
        return $this->belongsToMany(Station::class, "route_station", "route_id", "station_id")->withPivot('type', 'time');
    }
}

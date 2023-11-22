<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['departure_time', "arrival_time", "start_station", "end_station", 'car_id', "status", "driver_id"];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function start()
    {
        return $this->hasOne(Station::class, 'id', "start_station");
    }

    public function end()
    {
        return $this->hasOne(Station::class, "id", "end_station");
    }

    public function time_points()
    {
        return $this->hasMany(TimePoints::class);
    }
}

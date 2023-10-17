<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['departure_time', "arrival_time", 'car_id', "status", "driver_id"];

    public function Car()
    {
        return $this->belongsTo(Car::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }
}

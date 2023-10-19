<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['name', "address"];

    public function route()
    {
        return $this->belongsToMany(Route::class, 'route_station', 'station_id', 'route_id');
    }
}

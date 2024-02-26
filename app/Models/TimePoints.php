<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimePoints extends Model
{
    use HasFactory;

    protected $table = "time_points";
    protected $fillable = ['type', 'time', 'point_id', 'trip_id'];

    public function point()
    {
        return $this->belongsTo(Points::class, 'point_id', 'id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'id');
    }
}

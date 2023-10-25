<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['seat_id', 'trip_id', 'code', "pick_up_location", "drop_off_location", "status"];

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}

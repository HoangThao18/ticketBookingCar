<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}

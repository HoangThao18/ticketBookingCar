<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimePoints extends Model
{
    use HasFactory;

    protected $table = "time_points";

    public function point()
    {
        return $this->belongsTo(Points::class, 'point_id', 'id');
    }
}

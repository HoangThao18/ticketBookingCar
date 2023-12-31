<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = ['name', "license_plate", "number_seat", 'primary_img', 'type', 'status'];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getMorphClass()
    {
        return 'car';
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

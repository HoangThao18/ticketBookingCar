<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', "payment_method", 'status', 'code'];

    public function BillsDetail()
    {
        return $this->hasMany(BillDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }
}

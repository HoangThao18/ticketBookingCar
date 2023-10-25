<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    use HasFactory;

    protected $table = "bill_detail";

    protected $fillable = ['ticket_id', 'bill_id'];

    public function Ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

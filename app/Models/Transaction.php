<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = [
        'order_id',
        'from_name',
        'from_number_card',
        'type',
        'to_user',
        'to_number_card',
        'amount',
        'status'
    ];
}

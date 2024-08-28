<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;
    protected $table = "bank_transactions";
    protected $fillable = [
        'bank_id',
        'bank_brand_name',
        'acount_number',
        'transaction_date',
        'amount_out',
        'amount_in',
        'accumulated',
        'transaction_content',
        'reference_number'
    ];
}

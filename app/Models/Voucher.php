<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = "vouchers";
    protected $fillable = [
        'title',
        'code',
        'date_start',
        'date_end',
        'min_price',
        'discount_amount',
        'quantity',
        'used'
    ];
    public function status()
    {
        if ($this->date_start > Carbon::now()) {
            return 'comming';
        } else if ($this->date_end < Carbon::now()) {
            return 'expired';
        } else {
            return 'active';
        }
    }
    public function scopeActive($query)
    {
        return $query->where('date_start', '<=', Carbon::now())
            ->where('date_end', '>', Carbon::now())
            ->where('used', '<', 'quantity');
    }
}

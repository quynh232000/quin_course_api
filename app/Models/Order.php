<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "orders";
    protected $fillable = [
        'user_id',
        'email',
        'subtotal',
        'total',
        'status',
        'voucher_id',
        'payment_method',
        'order_code',
        'hash'
    ];
    protected $hidden = [
        'hash'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id');
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }
    public function delete()
    {
        $this->orderDetails()->delete();
        parent::delete();
    }
}

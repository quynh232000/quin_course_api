<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $table = "order_details";
    protected $fillable = [
        'order_id',
        'course_id',
        'price'
    ];
    public function course(){
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }
}

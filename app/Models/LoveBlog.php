<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoveBlog extends Model
{
    use HasFactory;
    protected $table = "love_blogs";

    protected $fillable = [
        'user_id',
        'blog_id'
    ];
}

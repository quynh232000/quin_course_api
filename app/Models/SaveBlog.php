<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaveBlog extends Model
{
    use HasFactory;
    protected $table = "save_blogs";
    protected $fillable = [
        'user_id',
        'blog_id'
    ];
}

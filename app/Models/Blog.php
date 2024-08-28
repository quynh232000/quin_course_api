<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $table = "blogs";

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail_url',
        'user_id',
        'from',
        'comment_count',
        'view_count',
        'is_show',
        'is_published',
        'published_at'
    ];
    public function tags()
    {
        return $this->hasMany(BlogTag::class, 'blog_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}

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
        'subtitle',
        'content_markdown',
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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function is_saved()
    {
        if (auth('api')->check()) {
            return SaveBlog::where(['blog_id' => $this->id, 'user_id' => auth('api')->id()])->exists();
        } else {
            return false;
        }
    }
    public function love_counts()
    {
        return LoveBlog::where('blog_id', $this->id)->count() ?? 0;
    }
    public function is_loved()
    {
        if (auth('api')->check()) {
            return LoveBlog::where(['blog_id' => $this->id, 'user_id' => auth('api')->id()])->exists();
        } else {
            return false;
        }
    }
    public function date_saved()
    {
        if ($this->is_saved()) {
            return SaveBlog::where(['blog_id' => $this->id, 'user_id' => auth('api')->id()])->first()->updated_at;
        }
        return null;
    }
    public function comment_count(){
        return Comment::where('commentable_id', $this->id)->where('type', 'blog')->count();
    }

}

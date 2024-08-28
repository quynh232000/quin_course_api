<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    use HasFactory;
    protected $table = "blog_tags";
    protected $fillable = [
        'tag_id',
        'blog_id'
    ];
    public function tag(){
        return $this->belongsTo(Tag::class, 'tag_id');
    }
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}

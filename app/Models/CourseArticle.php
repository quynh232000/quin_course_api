<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseArticle extends Model
{
    use HasFactory;
    protected $table = "course_articles";
    protected $fillable = [
        'step_id',
        'content'
    ];
    protected $hidden = [
        "created_at",
        'updated_at'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = "courses";

    protected $fillable = [
        'title',
        'sub_title',
        'slug',
        'image_url',
        'duration',
        'certificate_name',
        'user_id',
        'description',
        'completed_content',
        'price',
        'percent_sale',
        'level_id',
        'priority',
        'category_id',
        'type',
        'published_at',
        'view_count',
        'enrollment_count',
        'video_type',
        'video_url',
        'video',
        'status',

    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLecture extends Model
{
    use HasFactory;
    protected $table = "course_lectures";

    protected $fillable = [
        'uuid',
        'step_id',
        'description',
        'video_url',
        'video_type',
        'video',
        'priority',
        'is_show'
    ];
}

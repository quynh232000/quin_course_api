<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    use HasFactory;
    protected $table = "course_sections";

    protected $fillable = [
        'uuid',
        'course_id',
        'title',
        'will_learn',
        'priority',
        'is_show'
    ];
}

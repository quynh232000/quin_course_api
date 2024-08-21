<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseIntend extends Model
{
    use HasFactory;
    protected $table = "course_intends";

    protected $fillable = [
        'course_id',
        'type',
        'content'
    ];
}

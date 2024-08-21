<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseStep extends Model
{
    use HasFactory;
    protected $table = "course_steps";

    protected $fillable = [
        'uuid',
        'section_id',
        'title',
        'type',
        'priority',
        'is_preview',
    ];
    public function lecture()
    {
        return $this->hasOne(CourseLecture::class, 'step_id');
    }
}

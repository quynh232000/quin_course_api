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
        'duration'
    ];
    public function lecture()
    {
        return $this->hasOne(CourseLecture::class, 'step_id');
    }
    public function question()
    {
        return $this->hasOne(Question::class, 'parent_id');
    }
    public function answers()
    {
        return $this->hasMany(Answer::class, 'parent_id');
    }
    public function section(){
        return $this->belongsTo(CourseSection::class, 'section_id');
    }
}

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
    protected $hidden = [
        "created_at",
        // 'updated_at'
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
        return $this->hasMany(Answer::class, 'parent_id')->inRandomOrder();
    }
    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id');
    }
    public function next_step_uuid($course_id)
    {
        $section_ids = CourseSection::where('course_id', $course_id)->pluck('id')->all();
        $next_step = CourseStep::whereIn('section_id', $section_ids)->where('id', '>', $this->id)->orderBy('id', 'asc')->first();
        return $next_step ? $next_step->uuid : null;
    }
    public function previous_step_uuid($course_id)
    {
        $section_ids = CourseSection::where('course_id', $course_id)->pluck('id')->all();
        $previous_step = CourseStep::whereIn('section_id', $section_ids)->where('id', '<', $this->id)->orderBy('id', 'desc')->first();
        return $previous_step ? $previous_step->uuid : null;
    }
    public function is_enrollment_course($user_id)
    {
        $course_id = CourseSection::where('id', $this->section_id)->pluck('course_id')->first();
        // $course_id = $this->section->course_id;
        return Enrollment::where(['course_id' => $course_id, 'user_id' => $user_id, 'status' => 1])->exists();
    }

    public function sibling_steps()
    {
        return CourseStep::where('section_id', $this->section_id)->pluck('id')->all();
    }
    public function all_course_steps()
    {
        $sections_id = CourseSection::where('course_id', $this->course_id())->pluck('id')->all();

        return CourseStep::whereIn('section_id', $sections_id)->pluck('id')->all();
    }
    public function course_id()
    {
        return CourseSection::where('id', $this->section_id)->pluck('course_id')->first();
    }
}

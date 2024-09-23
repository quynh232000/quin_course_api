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
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function progress()
    {
        $data = [
            'intend' => [],
            'curriculum' => [],
            'landing' => [],
            'pricing' => [],
            'certificate' => [],
        ];
        $status = true;
        // check intended 
        $whatlearn = CourseIntend::where(['course_id' => $this->id, 'type' => 'whatlearn'])->count();
        if ($whatlearn < 4) {
            $data['intend'][] = "Specify at least 4 of your course's learning objectives";
            $status = false;
        }
        $require = CourseIntend::where(['course_id' => $this->id, 'type' => 'require'])->count();
        if ($require < 0) {
            $data['intend'][] = "Specify any course requirements or prerequisites";
            $status = false;
        }
        $whofor = CourseIntend::where(['course_id' => $this->id, 'type' => 'whofor'])->count();
        if ($whofor < 0) {
            $data['intend'][] = "Specify who this course if for";
            $status = false;
        }
        // check curriculum
        if (!$this->sections() || ($this->sections())->count() < 5) {
            $data['curriculum'][] = "Create at least 5 sections for your course";
            $status = false;
        }
        // check landing page
        if ($this->sub_title == null || $this->sub_title == '') {
            $data['landing'][] = 'Have a course Subtitle';
            $status = false;
        }
        if ($this->description == null || $this->description == '') {
            $data['landing'][] = 'Have a course description';
            $status = false;
        }
        if ($this->level_id == null || $this->level_id == '') {
            $data['landing'][] = 'Select level for this course';
            $status = false;
        }
        if ($this->image_url == null || $this->image_url == '') {
            $data['landing'][] = 'Upload image for this course';
            $status = false;
        }
        if ($this->video_url == null || $this->video_url == '') {
            $data['landing'][] = 'Upload video introduction for this course';
            $status = false;
        }
        // check price
        if ($this->price == null) {
            $data['pricing'][] = "Select a price for your course";
            $status = false;
        }
        // check certificate
        if ($this->certificate_name == null || $this->certificate_name == '') {
            $data['certificate'][] = "Provide a certificate name ";
            $status = false;
        }

        // process percent 
        $totalpercent = 100;
        $progress = [
            'intend' => true,
            'curriculum' => true,
            'landing' => true,
            'pricing' => true,
            'certificate' => true,
        ];
        if ($data['intend'] && count($data['intend']) > 0) {
            $progress['intend'] = false;
            $totalpercent -= 20;
        }
        if ($data['curriculum'] && count($data['curriculum']) > 0) {
            $progress['curriculum'] = false;
            $totalpercent -= 20;
        }
        if ($data['landing'] && count($data['landing']) > 0) {
            $progress['landing'] = false;
            $totalpercent -= 20;
        }
        if ($data['pricing'] && count($data['pricing']) > 0) {
            $progress['pricing'] = false;
            $totalpercent -= 20;
        }
        if ($data['certificate'] && count($data['certificate']) > 0) {
            $progress['certificate'] = false;
            $totalpercent -= 20;
        }
        return ['status' => $status, 'data' => $data, 'total_percent' => $totalpercent, 'progress' => $progress];
    }
    public function sections()
    {
        return $this->hasMany(CourseSection::class, 'course_id');
    }
    public function check_has_section($section_id)
    {
        return CourseSection::where(['course_id' => $this->id, 'id' => $section_id])->exists();
    }
    public function check_has_step($step_uuid)
    {
        $step = CourseStep::where('uuid', $step_uuid)->first();
        return $this->check_has_section($step->section_id);
        if ($step) {
        } else {
            return false;
        }
    }
    public function first_section()
    {
        return $this->sections()->first();
    }
    public function intends()
    {
        return $this->hasMany(CourseIntend::class, 'course_id');
    }
    public function rating()
    {
        $avg = Review::where('course_id', $this->id)->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }
    public function reviews()
    {
        // $user = null;
        // if (auth('api')->check()) {
        //     $user = auth('api')->user();
        // } else if (auth('admin')->check()) {
        //     $user = auth('admin')->user();
        // }
        // if ($user) {
        //     return Review::where('course_id', $this->id)->where('user_id', '!=', $user->id)->limit(10);
        // } else {
        // }
        return $this->hasMany(Review::class, 'course_id')->limit(10);
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function related_courses()
    {
        return Course::where('category_id', $this->category_id)->where('id', '!=', $this->id)->inRandomOrder()->limit(8);
    }
    public function enrollments()
    {
        // return $this->hasMany(Enrollment::class, 'course_id');
    }
    public function hasEnrollment($user_id)
    {
        return Enrollment::where('course_id', $this->id)->where('user_id', $user_id)->exists();
    }
    public function duration()
    {
        $total = 0;
        foreach ($this->sections as $section) {
            $total += $section->total_duration();
        }
        return $total;
    }

    public function total_sections()
    {
        return $this->sections()->count() ?? 0;
    }
    public function total_steps()
    {
        $section_ids = $this->sections()->pluck('id');
        $total_steps = CourseStep::whereIn('section_id', $section_ids)->count() ?? 0;
        return $total_steps;
    }
    public function my_review()
    {
        if (auth('api')->check()) {
            $review = Review::where(['user_id' => auth('api')->id(), 'course_id' => $this->id])->first();
            if ($review) {

                return ['is_log' => true, 'can_review' => true, 'review' => $review];
            } else {
                return ['is_log' => true, 'can_review' => false, 'review' => null];
            }


        } else {
            return ['is_log' => false, 'can_review' => false, 'review' => null];

        }

    }

    public function percent_learning($user_id)
    {
        $enrollment = Enrollment::where(['user_id' => $user_id, 'course_id' => $this->id])->first();
        $learningLog = LearningLog::where(['user_id' => $user_id, 'course_id' => $this->id])->first();
        if ($enrollment && $learningLog) {
            $total_steps = $this->total_steps();
            $completed_steps = count(json_decode($learningLog->user_progress));
            return ($completed_steps / $total_steps) * 100;
        } else {
            return 0;
        }
    }
}



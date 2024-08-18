<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\LevelCourse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function course_goals($id)
    {
        if (!$id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        return view("pages.teacher.course_goals", compact('course'));
    }

    public function course_curriculum($id)
    {
        if (!$id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        return view("pages.teacher.course_curriculum", compact('course'));
    }
    public function course_basics($id)
    {
        if (!$id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        $levels = LevelCourse::all();
        // $cate1 = Category::where('parent_id', 0)->get();
        $cate = new Category();
        $allCate = $cate->getAllParents($course->category_id);

        return view("pages.teacher.course_basics", compact('course', 'levels', 'cate', 'allCate'));
    }
    public function course_pricing($id)
    {
        if (!$id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        return view("pages.teacher.course_pricing", compact('course'));
    }
    public function course_certificate($id)
    {
        if (!$id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        return view("pages.teacher.course_certificate", compact('course'));
    }







}

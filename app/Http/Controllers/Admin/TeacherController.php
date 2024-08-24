<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Course;
use App\Models\Teacherinfo;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use Str;

class TeacherController extends Controller
{
    public function updateinfo(Request $request)
    {
        $banks = Bank::all();
        $teacherinfo = Teacherinfo::where('user_id', auth('admin')->user()->id)->with('bank')->first();
        return view('pages.teacher.updateinfo', compact('banks', 'teacherinfo'));
    }
    public function _updateinfo(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'bank_id' => 'required',
            'card_number' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', 'Please inter all information!');
        }

        if (!preg_match('/^\d{13,19}$/', $request->card_number)) {
            return redirect()->back()->withInput()->with('error', 'Invalid card number!');
        }
        if ($request->momo_number && !preg_match('/^(0[3|5|7|8|9])+([0-9]{8})\b$/', $request->momo_number)) {
            return redirect()->back()->withInput()->with('error', 'Invalid MoMo number!');
        }

        $teacherinfo = Teacherinfo::where('user_id', auth('admin')->user()->id)->first();
        if ($teacherinfo) {
            $teacherinfo->bank_id = $request->bank_id;
            $teacherinfo->card_number = $request->card_number;
            $teacherinfo->save();

        } else {
            $teacher = Teacherinfo::create([
                'user_id' => auth('admin')->user()->id,
                'bank_id' => $request->bank_id,
                'card_number' => $request->card_number
            ]);
            if ($request->momo_number) {
                $teacher->momo_number = $request->momo_number;
                $teacher->save();
            }
        }
        $checkRoleTeacher = UserRole::where(['user_id' => auth('admin')->user()->id, 'role_id' => '4'])->first();
        if (!$checkRoleTeacher) {
            UserRole::create([
                'user_id' => auth('admin')->user()->id,
                'role_id' => 4
            ]);
        }
        return redirect()->back()->with('success', 'Update info successfully!');
    }
    public function createCourse($step = 1)
    {
        $currrentstep = session('currrentstep');

        switch ($step) {
            case 1:
                return view('pages.teacher.createcourse', compact('step'));
            case 2:

                if (!$currrentstep || $currrentstep < $step - 1) {
                    return redirect()->back();
                }
                return view('pages.teacher.createcourse', compact('step'));
            case 3:
                if (!$currrentstep || $currrentstep < $step - 1) {
                    return redirect()->back();
                }
                $categories = Category::where('parent_id', 0)->get();
                return view('pages.teacher.createcourse', compact('step', 'categories'));
            default:
                return redirect()->back();

        }

    }
    public function _createCourse(Request $request, $step)
    {
        switch ($step) {
            case 1:
                $data = session('step') ?? [];
                $type = $request->typecourse;
                if (!$type) {
                    return redirect()->back()->with('error', 'Type course is required');
                }
                $data['type'] = $type;
                Session::put('step', $data);
                Session::put('currrentstep', 1);
                break;
            case 2:
                $data = session('step') ?? [];
                $title = $request->title;
                if (!$title) {
                    return redirect()->back()->with('error', 'Title course is required');
                }
                $data['title'] = $title;
                Session::put('step', $data);
                Session::put('currrentstep', 2);
                break;
            case 3:
                $data = session('step') ?? [];
                $category_id = $request->category_id;
                if (!$category_id) {
                    return redirect()->back()->with('error', 'Category course is required');
                }
                $data['category_id'] = $category_id;

                Session::put('step', $data);
                Session::put('currrentstep', 3);
                if (!$data['type']) {
                    return redirect('/course/create/1')->with('error', 'Type course is required');
                }
                if (!$data['title']) {
                    return redirect('/course/create/2')->with('error', 'title course is required');
                }

                $slug = Str::slug($data['title']);
                $countSlug = Course::where('slug', $slug)->count();
                if ($countSlug > 0) {
                    $slug .= "-" . $countSlug;
                }
                $course = Course::create([
                    'user_id' => auth('admin')->user()->id,
                    'slug' => $slug,
                    'title' => $data['title'],
                    'type' => $data['type'],
                    'category_id' => $data['category_id']
                ]);
                session()->forget('step');
                session()->forget('currrentstep');
                return redirect()->route('course.manage.goals', ['id' => $course->id]);


            default:
                return redirect()->back();

        }
        return redirect('course/create/' . ($step + 1));
    }
    public function deletecourse($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'ID course is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found');
        }
        if ($course->user_id !== auth('admin')->user()->id) {
            if (!in_array('Super Admin', auth('admin')->user()->roles()->toArray())) {
                return redirect()->back()->with('error', 'You are not authorized to delete this course');
            }
        }
        $course->delete();
        return redirect()->back()->with('success', 'Deleted course successfully!');
    }
}

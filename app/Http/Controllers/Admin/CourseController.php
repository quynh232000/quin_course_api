<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseIntend;
use App\Models\CourseSection;
use App\Models\LevelCourse;
use App\Services\YouTubeService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Validator;
use Str;

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

        $data['whatlearns'] = CourseIntend::where(['course_id' => $id, 'type' => 'whatlearn'])->get() ?? [];
        $data['requires'] = CourseIntend::where(['course_id' => $id, 'type' => 'require'])->get() ?? [];
        $data['whofors'] = CourseIntend::where(['course_id' => $id, 'type' => 'whofor'])->get() ?? [];
        return view("pages.teacher.course_goals", compact('course', 'data'));
    }
    public function _course_goals($id, Request $request)
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


        $contents = $request->content;
        $types = $request->type;
        $ids = $request->id;
        $data = [];
        foreach ($contents as $key => $value) {
            if ($value && $value != null) {
                if ($ids[$key]) {
                    CourseIntend::where('id', $ids[$key])->update([
                        'content' => $value,
                    ]);
                } else {
                    $data[] = [
                        'course_id' => $id,
                        'type' => $types[$key],
                        'content' => $value
                    ];
                }
                switch ($types[$key]) {
                    case 'whatlearn':

                        break;
                    case 'require':
                        break;
                    default:
                        break;
                }
            }
        }
        if (count($data) > 0) {
            CourseIntend::insert($data);
        }
        return redirect()->back()->withInput()->with('success', 'Updated Intended learners successfully!');

    }
    public function course_goals_delete($id, $goal_id)
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
        if (!$goal_id) {
            return redirect()->back()->with('error', 'Goal ID is required');
        }
        $goal = CourseIntend::find($goal_id);
        if (!$goal) {
            return redirect()->back()->with('error', 'Goal not found');
        }
        $goal->delete();
        return redirect()->back()->withInput()->with('success', 'Deleted Goal successfully!');
    }

    public function course_curriculum($id, $section_id = null)
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
        $sections = CourseSection::where('course_id', $id)->get();
        $sectiondetail = null;
        if ($section_id) {
            $sectiondetail = CourseSection::find($section_id);
        }

        return view("pages.teacher.course_curriculum", compact('course', 'sections', 'sectiondetail'));
    }
    public function _course_curriculum($id, Request $request, $section_id = null)
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

        $validate = Validator::make($request->all(), [
            'title' => 'required|string'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', 'Required title');
        }
        if ($section_id) {
            $section = CourseSection::find($section_id);
            if (!$section) {
                return redirect()->back()->withInput()->with('error', 'Section not found');
            }
            $section->title = $request->title;
            if ($request->will_learn && $request->will_learn != '') {
                $section->will_learn = $request->will_learn;
            }
            $section->save();
            return redirect()->back()->with('success', 'Updated section successfully!');

        } else {

            $data['uuid'] = Str::uuid();
            $data['course_id'] = $id;
            $data['title'] = $request->title;
            if ($request->will_learn && $request->will_learn != '') {
                $data['will_learn'] = $request->will_learn;
            }
            CourseSection::create($data);
            return redirect()->back()->with('success', 'Created new section successfully!');
        }
    }
    public function delete_section($id, $section_id)
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

        if (!$section_id) {
            return redirect()->back()->with('error', 'Section ID is required');
        }
        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $section->delete();
        return redirect()->back()->with('success', 'Deleted section successfully!');
    }
    public function course_curriculum_section($id, $section_id)
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
        return view('pages.teacher.course_curriculum_section', compact('course'));
    }
    public function course_curriculum_lecture($id, $section_id, $step_id)
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
        return view('pages.teacher.course_curriculum_lecture', compact('course'));
    }
    public function course_curriculum_quiz($id, $section_id, $step_id)
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
        return view('pages.teacher.course_curriculum_quiz', compact('course'));
    }
    public function course_curriculum_asm($id, $section_id, $step_id)
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
        return view('pages.teacher.course_curriculum_asm', compact('course'));
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
    public function _course_basics($id, Request $request)
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

        $mess = 'Update course successfully!';
        $type_mess = 'success';
        // get last category_id
        $category_ids = $request->category_id;
        $index = count($category_ids) - 1;
        while (!$category_ids[$index]) {
            $index--;
        }
        $newCate = $category_ids[$index];
        if ($newCate != $course->category_id) {
            $course->category_id = $newCate;
        }
        if ($request->title && $request->title != $course->title) {
            $course->title = $request->title;
        }
        if ($request->description && $request->description != $course->description) {
            $course->description = $request->description;
        }
        if ($request->level_id && $request->level_id != $course->level_id) {
            $course->level_id = $request->level_id;
        }
        if ($request->sub_title && $request->sub_title != $course->sub_title) {
            $course->sub_title = $request->sub_title;
        }
        // check image 
        if ($request->checkimage) {
            if ($request->checkimage == 'imagepc' && $request->hasFile('image')) {
                $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
                $course->image_url = $image_url;
            } else if ($request->checkimage == 'imageyoutube' && $request->image && $request->image != '') {
                $videoService = new YouTubeService();
                $checkVideo = $videoService->getVideoInfo($request->image);
                if ($checkVideo) {
                    $course->image_url = $checkVideo['thumbnail'];
                } else {
                    $type_mess = 'error';
                    $mess = 'Invalid YouTube video id';
                }
            }
        }
        // check video
        if ($request->checkvideo) {
            if ($request->checkvideo == 'videopc' && $request->hasFile('video')) {
                $file = Cloudinary::uploadVideo($request->video->getRealPath())->getSecurePath();
                $course->video_url = $file;
                $course->video_type = 'local';
            } else if ($request->checkvideo == 'videoyoutube' && $request->video && $request->video != '') {
                $videoService = new YouTubeService();
                $checkVideo = $videoService->getVideoInfo($request->video);
                if ($checkVideo) {
                    $course->video_url = 'https://www.youtube.com/embed/' . $request->video;
                    $course->video_type = 'youtube';
                    $course->video = $request->video;
                } else {
                    $type_mess = 'error';
                    $mess = 'Invalid YouTube video id';
                }
            }
        }

        $course->save();

        return redirect()->back()->with($type_mess, $mess);
        // return view("pages.teacher.course_basics", compact('course', 'levels', 'cate', 'allCate'));
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
    public function _course_pricing($id, Request $request)
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


        if ($request->price < 0) {
            return redirect()->back()->with('error', 'Price must be a positive number');
        }
        if ($request->price != $course->price) {
            $course->price = $request->price;
            $course->save();
        }
        if ($request->percent_sale && $request->percent_sale != $course->percent_sale) {
            if ($request->percent_sale > 0 && $request->percent_sale < 100) {
                $course->percent_sale = $request->percent_sale;
                $course->save();

            } else {
                return redirect()->back()->with('error', 'Percent sale must be between 0 and 100');
            }
        }
        return redirect()->back()->with('success', 'Course pricing updated successfully');
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
    public function _course_certificate($id, Request $request)
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
        $validate = Validator::make($request->all(), [
            'certificate_name' => 'required|string'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', 'Required name certificate');
        }
        $certificate_name = $request->certificate_name;
        if ((strlen($certificate_name) <= 10) || (strlen($certificate_name) >= 255)) {
            return redirect()->back()->withInput()->with('error', 'Name certificate must be between 10 and 255 characters');
        }
        if ($certificate_name != $course->certificate_name) {
            $course->certificate_name = $certificate_name;
            $course->save();
        }

        return redirect()->back()->with('success', 'Updated name certificate successfully');
    }
    public function instructor()
    {

        $userroles = auth('admin')->user()->roles()->toArray();
        $checkRoleAdmin = in_array('Super Admin', $userroles);
        if ($checkRoleAdmin) {
            $courses = Course::latest()->get();
        } else {
            $courses = Course::where('user_id', auth('admin')->user()->id)->latest()->get();
        }

        return view('pages.teacher.course_instructor', compact('courses'));
    }









}

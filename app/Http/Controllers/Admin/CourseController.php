<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseIntend;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Models\CourseStep;
use App\Models\LevelCourse;
use App\Models\Question;
use App\Services\YouTubeService;
use Carbon\Carbon;
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
        if (!$section_id) {
            return redirect()->back()->with('error', 'Section ID is required');
        }
        $section = CourseSection::where('id', $section_id)->with('steps')->first();
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }

        // get all step of this section


        return view('pages.teacher.course_curriculum_section', compact('course', 'section'));
    }
    public function edit_title_section_step($id, $section_id, $step_id, Request $request)
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

        if (!$step_id) {
            return redirect()->back()->with('error', 'Missing step ID');
        }
        $step = CourseStep::find($step_id);
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }
        if ($request->title != '' && $request->title != $step->title) {
            $step->title = $request->title;
            $step->save();
        }
        return redirect()->back()->with('success', 'Update Step Success');


    }
    public function delete_step($id, $section_id, $step_id)
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

        if (!$step_id) {
            return redirect()->back()->with('error', 'Missing step ID');
        }
        $step = CourseStep::find($step_id);
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }
        if ($step->type == 'lecture') {
            $lecture = CourseLecture::where('step_id', $step->id)->first();
            if ($lecture) {
                $lecture->delete();
            }
        } else if ($step->type == 'quiz') {
            $question = Question::where('parent_id', $step->id)->first();
            if ($question) {
                $question->delete();
            }
            $answers = Answer::where('parent_id', $step->id)->get();
            foreach ($answers as $answer) {
                $answer->delete();
            }
        }
        $step->delete();
        return redirect()->back()->with('success', 'Deleted Step Success');


    }
    public function _course_curriculum_section($id, $section_id, Request $request)
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
        $validate = Validator::make($request->all(), [
            'title' => 'required|string',
            'type' => 'required|string'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', 'Required title and type');
        }
        if ($request->type == 'asm') {
            return redirect()->back()->with('error', 'Assignment is not supported yet. It will come back later.');
        }
        CourseStep::create([
            'uuid' => Str::uuid(),
            'section_id' => $section_id,
            'title' => $request->title,
            'type' => $request->type
        ]);
        return redirect()->back()->with('success', 'Created new step successfully!');


    }
    public function course_curriculum_lecture($id, $section_id, $step_id)
    {
        if (!$id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course || !$section_id || !$step_id) {
            return redirect('/notfund')->withInput()->with('message', "Missing ID course or section ID or step ID");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }

        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $step = CourseStep::where('id', $step_id)->with('lecture')->first();
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }


        return view('pages.teacher.course_curriculum_lecture', compact('course', 'section', 'step'));
    }
    public function _course_curriculum_lecture($id, $section_id, $step_id, Request $request)
    {
        if (!$id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course || !$section_id || !$step_id) {
            return redirect('/notfund')->withInput()->with('message', "Missing ID course or section ID or step ID");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }

        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $step = CourseStep::find($step_id);
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }
        $validate = Validator::make($request->all(), [
            'description' => 'required|string'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', 'Required description');
        }
        $mess = 'Update lecture information successfully!';
        $type_mess = 'success';

        $lecture = CourseLecture::where('step_id', $step->id)->first();
        if (!$lecture) {
            $lecture = new CourseLecture();
            $lecture->step_id = $step_id;
            $lecture->uuid = Str::uuid();
            $lecture->description = '';
        }
        // check video

        if ($request->checkvideo) {
            if ($request->checkvideo == 'videopc' && $request->hasFile('video')) {
                $file = Cloudinary::uploadVideo($request->video->getRealPath())->getSecurePath();

                $duration = $request->duration ?? 0;
                // get duration clound
                $lecture->video_type = 'local';
                $step->duration = $duration;
                $lecture->video_url = $file;
            } else if ($request->checkvideo == 'videoyoutube' && $request->video && $request->video != '') {
                $videoService = new YouTubeService();
                $checkVideo = $videoService->getVideoInfo($request->video);
                if ($checkVideo) {
                    $duration = $checkVideo['duration']['total_seconds'];
                    $file = 'https://www.youtube.com/embed/' . $request->video;
                    $lecture->video_type = 'youtube';
                    $lecture->video_url = $file;
                    $lecture->video = $request->video;
                    $step->duration = $duration;

                } else {
                    $type_mess = 'error';
                    $mess = 'Invalid YouTube video id';
                }
            }
        }

        $lecture->description = $request->description;

        $lecture->save();
        $step->save();

        return redirect()->back()->with($type_mess, $mess);
    }
    public function course_curriculum_quiz($id, $section_id, $step_id)
    {
        if (!$id || !$section_id || !$step_id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $step = CourseStep::find($step_id);
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }

        return view('pages.teacher.course_curriculum_quiz', compact('course', 'section', 'step'));
    }
    public function course_quiz_addanswer($id, $section_id, $step_id, Request $request)
    {
        if (!$id || !$section_id || !$step_id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $step = CourseStep::find($step_id);
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }
        if ($request->indexcheck == '') {
            return redirect()->back()->with('error', 'At least one answer must be provided');
        }

        $indexcheck = $request->indexcheck;

        $ids = $request->id;
        $answers = $request->answer;
        $explains = $request->explain;
        $data = [];

        foreach ($answers as $key => $value) {
            if ($value && $value != null) {
                if ($ids[$key]) {
                    Answer::where('id', $ids[$key])->update([
                        'content' => $value,
                        'explain' => $explains[$key],
                        'is_correct' => $indexcheck == $key ? true : false
                    ]);
                } else {
                    $data[] = [
                        'parent_id' => $step_id,
                        'content' => $value,
                        'explain' => $explains[$key],
                        'is_correct' => $indexcheck == $key ? true : false,
                        'from' => 'course_step'
                    ];
                }
            }
        }
        if (count($data) > 0) {
            Answer::insert($data);
        }

        return redirect()->back()->with('success', 'Update Course Answers successfully!');
    }
    public function quiz_addquestion($id, $section_id, $step_id, Request $request)
    {
        if (!$id || !$section_id || !$step_id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $step = CourseStep::where('id', $step_id)->with('question')->first();
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }

        $validate = Validator::make($request->all(), [
            'content' => 'required|string'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', 'Required content');
        }
        $question = Question::where('parent_id', $step->id)->first();
        if ($question) {
            $question->content = $request->content;
            $question->save();
        } else {
            Question::create([
                'content' => $request->content,
                'parent_id' => $step->id,
                'from' => 'course_step'
            ]);
        }

        return redirect()->back()->with('success', 'Update question successfully!');

    }
    public function quiz_setduration($id, $section_id, $step_id, Request $request)
    {
        if (!$id || !$section_id || !$step_id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $step = CourseStep::where('id', $step_id)->with('question')->first();
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }

        $validate = Validator::make($request->all(), [
            'duration' => 'required'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', 'Required duration');
        }
        if (!$request->duration || $request->duration < 60) {
            return redirect()->back()->with('error', 'Duration must be a positive number and must be more than 60s.');
        }
        $step->duration = $request->duration;
        $step->save();
        return redirect()->back()->with('success', 'Update duration successfully!');

    }
    public function quiz_deleteanswer($id, $section_id, $step_id, $answer_id)
    {
        if (!$id || !$section_id || !$step_id || !$answer_id) {
            return redirect('/notfund')->withInput()->with('message', 'Course ID is required');
        }
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfund')->withInput()->with('message', "Course ID {$id} not found");
        }
        if ($course->user_id != auth('admin')->user()->id) {
            return redirect('/notfund')->withInput()->with('message', 'You are not authorized to access this course');
        }
        $section = CourseSection::find($section_id);
        if (!$section) {
            return redirect()->back()->with('error', 'Section not found');
        }
        $step = CourseStep::where('id', $step_id)->with('question')->first();
        if (!$step) {
            return redirect()->back()->with('error', 'Step not found');
        }

        $answer = Answer::find($answer_id);
        if (!$answer) {
            return redirect()->back()->with('error', 'Answer not found');
        }
        $answer->delete();

        return redirect()->back()->with('success', 'Delete answer successfully!');

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
        // dd($request->percent_sale);
        if (isset($request->percent_sale) && $request->percent_sale != $course->percent_sale) {
            if ($request->percent_sale >= 0 && $request->percent_sale <= 100) {
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
            $courses = Course::where('deleted_at',null)->latest()->get();
        } else {
            $courses = Course::where(['user_id'=> auth('admin')->user()->id,'deleted_at'=>null])->latest()->get();
        }

        return view('pages.teacher.course_instructor', compact('courses'));
    }

    // preview course
    public function preview($id)
    {
        $course = Course::find($id);
        $step = $course->first_section()->first_step();

        if (!$course || !$step) {
            return redirect('/notfound')->with('message', 'Course not found');
        }
        return redirect()->route('preview_home', ['id' => $course->id, 'type' => $step->type, 'uuid' => $step->uuid]);
    }
    public function preview_home($id, $type, $uuid)
    {
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfound')->with('message', 'Course or step not found');
        }
        $step = CourseStep::where('uuid', $uuid)->first();
        return view('pages.course.preview_course', compact('course', 'step'));
    }
    public function published_course($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return redirect('/notfound')->with('message', 'Course or step not found');
        }
        if ($course->published_at && $course->progress()['status']) {
            $course->published_at = null;
            $mess = 'Course is pravated';
        } else {
            $course->published_at = Carbon::now();
            $mess = 'Course is published';
            $course->duration = $course->duration();
        }
        $course->save();
        return redirect()->back()->with('message', $mess);
    }


}

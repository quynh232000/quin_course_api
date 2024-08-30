<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseStep;
use App\Models\Enrollment;
use App\Models\LearningLog;
use App\Models\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class Coursecontroller extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/course/filter",
     *      operationId="coursefilter",
     *      tags={"Course"},
     *      summary="Filter courses",
     *      description="Returns list course",
     *      @OA\Parameter(
     *         description="Page number for filtering results",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Limit per page number for filtering results",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Type of course: free, sale, popular",
     *         in="query",
     *         name="type",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */
    public function filter_course(Request $request)
    {
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $query = Course::whereNotNull('published_at')
                ->where(['deleted_at' => null])->with(['user']);
            // condition
            // if ($request->has('min_price') && $request->min_price > 0) {
            //     $query->where('price', '>=', $request->min_price);
            // }
            // if ($request->has('max_price') && $request->max_price > 0) {
            //     $query->where('price', '<=', $request->max_price);
            // }
            if ($request->type && $request->type != '') {
                switch ($request->type) {
                    case 'free':
                        $query->where('price', 0)->orWhere('price', null);
                        break;
                    case 'sale':
                        $query->where('percent_sale', '>', 0);
                        break;
                    case 'popular':
                        $query->orderBy('enrollment_count', "DESC");
                        break;
                }
            }







            $data = $query->paginate($limit, ['*'], 'page', $page);
            $data->getCollection()->transform(function ($item) {
                $item->rating = $item->rating();
                return $item;
            });
            return Response::json(true, 'Get list of courses successfully!', $data->items(), [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ]);
        } catch (\Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/api/course/detail/{slug}",
     *      operationId="detailvourse",
     *      tags={"Course"},
     *      summary="Get course detail by slug",
     *      description="Returns course information",
     *      @OA\Parameter(
     *         description="Slug of thif course",
     *         in="path",
     *         name="slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ), 
     * )
     */
    public function detail_course($slug)
    {
        try {
            if (!$slug) {
                return Response::json(false, 'Missing parameter Slug course');
            }
            $course = Course::where('slug', $slug)->with(['category', 'user', 'sections.steps', 'reviews.user'])->first();
            // , 'reviews.user', 'category.getAllParents', 'related_courses'
            if (!$course) {
                return Response::json(false, 'Not found course with slug: ' . $slug);
            }

            $course->rating = $course->rating();
            $course->category->parent = $course->category->getAllParents();
            $related_courses = $course->related_courses();
            return Response::json(true, 'Get detail course successfully!', ['course' => $course, 'related_courses' => $related_courses]);
        } catch (\Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/course/enroll/{course_id}",
     *      operationId="enroll",
     *      tags={"Course"},
     *      summary="Enrollcourse free",
     *      description="",
     *      @OA\Parameter(
     *         description="Course ID ",
     *         in="path",
     *         name="course_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *        security={{
     *         "bearer": {}
     *     }},
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ), 
     * )
     */
    public function enroll_course($course_id)
    {
        try {
            if (!$course_id) {
                return Response::json(false, 'Missing parameter course_id');
            }
            $course = Course::find($course_id);
            if (!$course) {
                return Response::json(false, 'Course not found');
            }
            if ($course->price > 0) {
                return Response::json(false, 'Course is not free. You can not enrrol this course.');
            }
            $user = auth('api')->user();
            if ($user->isEnrolled($course_id)) {
                return Response::json(false, 'You have already enrolled this course');
            }
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course_id,
                'start_date' => Carbon::now()
            ]);
            $course->enrollment_count = $course->enrollment_count + 1;
            $course->save();
            return Response::json(true, 'Enroll course successfully!');
        } catch (\Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());

        }
    }

    /**
     * @OA\Get(
     *      path="/api/course/learning/{course_slug}",
     *      operationId="coursedetailbyslug",
     *      tags={"Course"},
     *      summary="Get course information by slug",
     *      description="Returns course information",
     *      @OA\Parameter(
     *         description="Slug of thif course",
     *         in="path",
     *         name="course_slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }},
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ), 
     * )
     */
    public function get_course_info($course_slug)
    {
        try {
            if (!$course_slug) {
                return Response::json(false, 'Missing parameter course_slug');
            }
            $course = Course::where('slug', $course_slug)->first();
            if (!$course) {
                return Response::json(false, 'Course not found');
            }
            // check user is enrolled
            $user = auth('api')->user();
            if (!$course->hasEnrollment($user->id)) {
                return Response::json(false, 'You are not enrolled in this course');
            }
            // check learning log 
            $learning_log = LearningLog::where(['course_id' => $course->id, 'user_id' => $user->id])->first();
            if (!$learning_log) {
                $first_step = $course->first_section()->first_step();
                $learning_log = LearningLog::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'current_step' => $first_step->uuid,
                    'user_progress' => json_encode([$first_step->id]),
                    'time_start' => Carbon::now(),
                    'is_completed' => false
                ]);
            }
            // get data info
            $data['sections'] = $course->sections->map(function ($section) {
                $section->steps = $section->steps;
                return $section;
            });

            // get last step learning uuid 
            // $progress_ids = json_decode($learning_log->user_progress);
           
            // $end_id = end($progress_ids);
            // $last_step = CourseStep::where('id',$end_id)->first();

            $data['learning_log'] =$learning_log;



            $data['user_progress'] = json_decode($learning_log->user_progress);
            return Response::json(true, 'success', $data);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/course/user_progress/{course_slug}/{step_uuid}",
     *      operationId="user_progress",
     *      tags={"Course"},
     *      summary="user_progress",
     *      description="user_progress",
     *      @OA\Parameter(
     *         description="Slug of thif course",
     *         in="path",
     *         name="course_slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Step uuid of thif course",
     *         in="path",
     *         name="step_uuid",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }},
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ), 
     * )
     */
    public function user_progress($course_slug, $step_uuid)
    {
        try {
            if (!$course_slug || !$step_uuid) {
                return Response::json(false, 'Missing parameter course_slug');
            }
            $course = Course::where('slug', $course_slug)->first();
            if (!$course) {
                return Response::json(false, 'Course not found');
            }
            // check user is enrolled
            $user = auth('api')->user();
            if (!$course->hasEnrollment($user->id)) {
                return Response::json(false, 'You are not enrolled in this course');
            }
            // check step is blong to course 
            $step = CourseStep::where('uuid', $step_uuid)->first();
            if (!$step) {
                return Response::json(false, 'Step not found');
            }
            if (!$course->check_has_section($step->section_id)) {
                return Response::json(false, 'This step is not belong to this course');
            }
            // check learning log 
            $learning_log = LearningLog::where(['course_id' => $course->id, 'user_id' => $user->id])->first();

            // check step with learning log
            if ($step->uuid != $learning_log->current_step) {
                return Response::json(false, 'Step uuid doesnt match with current learning log');
            }
            // compare time to study
            $time_next = Carbon::parse($learning_log->time_start);

            $time_next->addSeconds(round($step->duration * 0.8));
            if ($time_next->greaterThan(Carbon::now())) {
                $waitTime = $time_next->diffForHumans(Carbon::now(), true);
                return Response::json(false, 'You cannot study this step now. Please wait until ' . $waitTime);
            }
            // save change next step 
            $next_step_uuid = $step->next_step_uuid($course->id);
            if ($next_step_uuid) {
                $next_step_id = CourseStep::where('uuid', $next_step_uuid)->pluck('id')->first();
                $learning_log->current_step = $next_step_uuid;

                $user_progress = json_decode($learning_log->user_progress);
                array_push($user_progress, $next_step_id);
                $learning_log->user_progress = json_encode($user_progress);

                // Set the time start to the current time
                $learning_log->time_start = Carbon::now();
            } else {
                $learning_log->is_completed = true;
            }
            $learning_log->save();
            return Response::json(true, 'Save your lesstion successfully!', ['next_step_uuid' => $next_step_uuid]);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/api/course/step/{course_slug}/{step_uuid}",
     *      operationId="step_info",
     *      tags={"Course"},
     *      summary="step_info",
     *      description="step_info",
     *      @OA\Parameter(
     *         description="Slug of thif course",
     *         in="path",
     *         name="course_slug",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Step uuid of thif course",
     *         in="path",
     *         name="step_uuid",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     security={{
     *         "bearer": {}
     *     }},
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ), 
     * )
     */
    public function step_info($course_slug, $step_uuid)
    {
        try {
            if (!$course_slug || !$step_uuid) {
                return Response::json(false, 'Missing parameter course_slug');
            }
            $course = Course::where('slug', $course_slug)->first();
            if (!$course) {
                return Response::json(false, 'Course not found');
            }
            // check user is enrolled
            $user = auth('api')->user();
            if (!$course->hasEnrollment($user->id)) {
                return Response::json(false, 'You are not enrolled in this course');
            }
            // check step is blong to course 
            $step = CourseStep::where('uuid', $step_uuid)->first();
            if (!$step) {
                return Response::json(false, 'Step not found');
            }
            if (!$course->check_has_section($step->section_id)) {
                return Response::json(false, 'This step is not belong to this course');
            }
            // check learning log 
            $learning_log = LearningLog::where(['course_id' => $course->id, 'user_id' => $user->id])->first();

            // check step with learning log
            $user_progress = json_decode($learning_log->user_progress);

            if (!in_array($step->id, $user_progress)) {
                return Response::json(false, "This step is not belong to your progress course. Please study in  progress's course");
            }
            switch ($step->type) {
                case 'lecture':
                    $step->lecture = $step->lecture;
                    break;
                case 'quiz':
                    $step->question = $step->question;
                    $step->answers = $step->answers;
                    break;
                default:
                    break;
            }
            return Response::json(
                true,
                'Get step info successfully!',
                [
                    'course' => $course,
                    'learning_log' => $learning_log,
                    'step' => $step,
                    'previous_step_uuid' => $step->previous_step_uuid($course->id),
                    'next_step_uuid' => $step->next_step_uuid($course->id)
                ]
            );
        } catch (Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());
        }
    }



    /**
     * @OA\Get(
     *      path="/api/test/{uuid}",
     *      operationId="test",
     *      tags={"Course"},
     *      summary="Get course detail by slug",
     *      description="Returns course information",
     *      @OA\Parameter(
     *         description="Slug of thif course",
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ), 
     * )
     */
    public function test($uuid)
    {
        // $binaryUuid = pack('H*', str_replace('-', '', $uuid));  // Convert the UUID to binary
        // $obfuscatedUuid = bin2hex($binaryUuid);
        $base64 = base64_encode($uuid); // Encode in base64
        $customId = rtrim(strtr($base64, '+/', '-_'), '=');

        $padded = str_pad(strtr($customId, '-_', '+/'), strlen($customId) % 4, '=', STR_PAD_RIGHT);

        // Base64 decode to get the original UUID string
        $decoded = base64_decode($padded);
        return Response::json(true, 'ok', ['customId' => $customId, 'decode' => $decoded]);
    }
}



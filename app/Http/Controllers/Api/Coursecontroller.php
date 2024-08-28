<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Response;
use Carbon\Carbon;
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
}

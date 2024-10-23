<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Response;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/reviews/create",
     *      operationId="create review",
     *      tags={"Review"},
     *      summary="Create a new review",
     *      description="Returns new new review information",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="course_id",
     *                     description="",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="content",
     *                     description="Content",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="rating",
     *                     description="rating",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function create_review(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'course_id',
                'rating',
                'content'
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Missing parameter', $validate->errors());
            }
            if($request->rating <1 || $request->rating>5){
                return Response::json(false, 'Invalid rating. Rating should be between 1 and 5');
            }
            $check_course = Course::where('id',$request->course_id)->first();
            if(!$check_course) {
                return Response::json(false, 'Course not found');
            }


            $user_id = auth('api')->id();
            $check_review = Review::where(['user_id' => $user_id, 'course_id' => $request->course_id])->first();
            if ($check_review) {
                $check_review->content = $request->input('content');
                $check_review->rating = $request->rating;
                $check_review->save();
                $check_review->user = $check_review->user;
                return Response::json(true, 'Review updated successfully', $check_review);

            } else {
                $can_review = Enrollment::where(['course_id' => $request->course_id, 'user_id' => $user_id])->first();
                if (!$can_review) {
                    return Response::json(false, 'You cannot review this course when dont enroll this course');
                }
                $review = Review::create([
                    'user_id' => $user_id,
                    'course_id' => $request->course_id,
                    'rating' => $request->rating,
                    'content' => $request->input('content')
                ]);
                $review->user = $review->user;
                return Response::json(true, 'Review created successfully', $review);
            }
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
}

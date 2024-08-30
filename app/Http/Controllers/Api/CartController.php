<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Response;
use Exception;
use Illuminate\Http\Request;
use Number;

class CartController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/cart/addcart/{id}",
     *      operationId="addcart",
     *      tags={"Cart"},
     *      summary="Add to your cart",
     *      description="",
     *      @OA\Parameter(
     *         description="ID of this course",
     *         in="path",
     *         name="id",
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
    public function addcart($id)
    {
        try {
            if (!$id) {
                return Response::json(false, 'Missing required parameter: id');
            }
            $course = Course::find($id);
            if (!$course) {
                return Response::json(false, 'Course not found');
            }
            if($course->price ==0){
                return Response::json(false, 'Course is free. You can not add this course to cart. Enroll this.');
            }
            $checkCart = Cart::where(['course_id' => $id, 'user_id' => auth('api')->id()])->first();
            if (!$checkCart) {
                Cart::create([
                    'course_id' => $id,
                    'user_id' => auth('api')->id(),
                ]);
                return Response::json(true, 'Course added to cart');
            }
            return Response::json(true, 'Course already in cart');
        } catch (Exception $e) {
            return Response::json(false, 'Error: ', $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *      path="/api/cart/deletecart/{id}",
     *      operationId="deletecart",
     *      tags={"Cart"},
     *      summary="Delete course from your cart",
     *      description="",
     *      @OA\Parameter(
     *         description="ID of this course",
     *         in="path",
     *         name="id",
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
    public function deletecart($id)
    {
        try {
            if (!$id) {
                return Response::json(false, 'Missing required parameter: id');
            }
            $course = Course::where('id', $id)->first();
            if (!$course) {
                return Response::json(false, 'Course not found');
            }
            $checkCart = Cart::where(['course_id' => $id, 'user_id' => auth('api')->id()])->first();
            if ($checkCart) {
                $checkCart->delete();
                return Response::json(true, 'Course removed from cart successfully');
            }
            return Response::json(false, 'Course not in cart');
        } catch (Exception $e) {
            return Response::json(false, 'Error: ', $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *      path="/api/cart/asyn_cart",
     *      operationId="asyn_cart",
     *      tags={"Cart"},
     *      summary="Asynchronous cart update",
     *      description="Update courses in your cart asynchronously by providing an array of course IDs.",
     *      @OA\Parameter(
     *          name="id[]",
     *          in="query",
     *          required=true,
     *          @OA\Schema(
     *              type="array",
     *              @OA\Items(type="number")
     *          ),
     *          description="Array of course IDs"
     *      ),
     *      security={{
     *          "bearer": {}
     *      }},
     *      @OA\Response(
     *          response=400,
     *          description="Invalid ID supplied"
     *      ),
     * )
     */
    public function asyn_cart(Request $request)
    {
        try {
            $ids = $request->id;
            $cart_ids = Cart::where('user_id', auth('api')->user()->id)->pluck('course_id')->toArray();
            $new_ids = array_diff($ids, $cart_ids);
            $has_ids=[];
            foreach ($new_ids as $id) {
                $course = Course::find($id);
                if ($course) {
                    $has_ids[]=(integer)$id;
                    Cart::create([
                        'course_id' => $id,
                        'user_id' => auth('api')->user()->id,
                    ]);
                }
            }
            return Response::json(true, 'Update Asyn cart successfully', array_merge($cart_ids, $has_ids));
        } catch (Exception $e) {
            \Log::error('Error updating asynchronous cart: ' . $e->getMessage());
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
/**
     * @OA\Get(
     *      path="/api/cart/my_cart",
     *      operationId="my_cart",
     *      tags={"Cart"},
     *      summary="my_cart ",
     *      description="Get my cart ",
     *    
     *      security={{
     *          "bearer": {}
     *      }},
     *      @OA\Response(
     *          response=400,
     *          description="Invalid ID supplied"
     *      ),
     * )
     */
    public function my_cart(){
        try {
            $carts = Cart::where('user_id',auth('api')->id())->with('course')->get();
            return Response::json(true,'Get my cart successfully',$carts);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
}

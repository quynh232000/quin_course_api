<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Response;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
        /**
* @OA\Get(

   *      path="/api/category/get_child/{id}",
  *      operationId="getchildcategory",
  *      tags={"Category"},
  *      summary="Get category child information by id",
  *      description="Returns category child information",
*    @OA\Parameter(
*          name="id",
*          description="id category child",
*          required=true,
*          in="path",
*          @OA\Schema(
*              type="string"
*          )
*      ),
*     @OA\Response(
*         response=400,
*         description="Invalid ID supplied"
*     ),
* 
*     
* )
*/
    public function get_child($id)
    {
        if (!$id) {
            return Response::json(false, 'Missing parameter Id category');
        }
        $data = Category::where('parent_id', $id)->get()->map(function ($category){
            $category->haschild = $category->hasChild();
            return $category;
        });
        return Response::json(true, 'Success', $data ??[]);
    }
}

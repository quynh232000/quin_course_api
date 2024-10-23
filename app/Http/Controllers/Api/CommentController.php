<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\CourseStep;
use App\Models\Reaction;
use App\Models\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Validator;

class CommentController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/comments/create",
     *      operationId="create_comment",
     *      tags={"Comment"},
     *      summary="Create a new create_comment",
     *      description="Returns new new create_comment information",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="comment",
     *                     description="Your comment",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="commentable_id",
     *                     description="ID",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="type",
     *                     description="Type commentable: step,blog,comment",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function create_comment(Request $request)
    {
        try {
            // validation
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
                'type' => 'required',
                'commentable_id' => 'required',
            ]);
            if ($validator->fails()) {
                return Response::json(false, $validator->errors());
            }
            if ($request->type == 'step') {
                $step = CourseStep::where('id', $request->commentable_id)->first();
                if (!$step) {
                    return Response::json(false, 'Step not found');
                }
            } else if ($request->type == 'blog') {
                $blog = Blog::where('id', $request->commentable_id)->first();
                if (!$blog) {
                    return Response::json(false, 'Blog not found');
                }
            } else if ($request->type == 'comment') {
                $comment = Comment::where('id', $request->commentable_id)->first();
                if (!$comment) {
                    return Response::json(false, 'Comment not found');
                }
            }

            $comment = Comment::create([
                'comment' => $request->comment,
                'commentable_id' => $request->commentable_id,
                'type' => $request->type,
                'user_id' => auth('api')->id()
            ]);
            $comment->commentor = $comment->commentor;

            // get is reaction or not
            // $item->reactions = $item->reactions();
            $comment->reaction_count = $comment->reaction_count();
            // $comment->replies = $comment->replies();
            $comment->replies_count = $comment->replies_count();


            $type_reaction = ($request->type == 'step' || $request->type == 'comment') ? 'comment' : 'blog';
            $comment->is_reaction = $comment->is_reaction($type_reaction) ? true : false;
            $comment->type_reaction = $comment->is_reaction($type_reaction);

            $comment->all_reaction_type = $comment->all_type_reactions($type_reaction);

            return Response::json(true, 'Comment created successfully', $comment);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/api/comments/list/{commentable_id}",
     *      operationId="listcomments",
     *      tags={"Comment"},
     *      summary="Filter listcomments",
     *      description="Returns  listcomments",
     *      @OA\Parameter(
     *         description="Commentable_id",
     *         in="path",
     *         name="commentable_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         )
     *     ),
     *     @OA\Parameter(
     *         description="Type of comment: step, blog, comment",
     *         in="query",
     *         name="type",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
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
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */
    public function get_list_comments(Request $request, $commentable_id)
    {
        try {
            if (!$request->type) {
                return Response::json(false, 'Missing type of commentable');
            }
            if (!$commentable_id) {
                return Response::json(false, 'Missing commentable ID');
            }

            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;

            $query = Comment::where('is_deleted', 0);

            switch ($request->type) {
                case 'step':
                    $step = CourseStep::where('id', $commentable_id)->first();
                    if (!$step) {
                        return Response::json(false, 'Step not found');
                    }

                    break;
                case 'blog':
                    $blog = Blog::where('id', $commentable_id)->first();
                    if (!$blog) {
                        return Response::json(false, 'blog not found');
                    }

                    break;
                case 'comment':
                    $comment = Comment::where('id', $commentable_id)->first();
                    if (!$comment) {
                        return Response::json(false, 'comment not found');
                    }

                    break;

                default:
                    break;
            }
            $query->where(['commentable_id' => $commentable_id, 'type' => $request->type])->orderBy('updated_at', 'desc');
            $data = $query->paginate($limit, ['*'], 'page', $page);
            $data->getCollection()->transform(function ($item) use ($request) {
                $item->commentor;
                // $item->reactions = $item->reactions();
                $item->reaction_count = $item->reaction_count();
                // $item->replies = $item->replies();
                $item->replies_count = $item->replies_count();

                // get is reaction or not
                $type_reaction = ($request->type == 'step' || $request->type == 'comment') ? 'comment' : 'blog';
                $item->is_reaction = $item->is_reaction($type_reaction) ? true : false;
                $item->type_reaction = $item->is_reaction($type_reaction);

                $item->all_reaction_type = $item->all_type_reactions($type_reaction);
                return $item;
            });

            return Response::json(true, 'Get list comments successfully!', $data->items(), [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
            ]);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *      path="/api/reaction/{id}",
     *      operationId="changereaction",
     *      tags={"Comment"},
     *      summary="changereaction",
     *      description="changereaction",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID : ID Comment, ID blog",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object"
     *                 ,@OA\Property(
     *                     property="type",
     *                     description="like,love,crush,haha,wow,sad,angry",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="commentable_type",
     *                     description="comment, blog",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function reaction_comment(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'type' => 'required',
                'commentable_type' => 'required',
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Missing parameter', $validate->errors());
            }


            if (!$id) {
                return Response::json(false, 'Missing comment ID');
            }

            if ($request->commentable_type == 'comment') {
                $comment = Comment::where('id', $id)->first();
                if (!$comment) {
                    return Response::json(false, 'Comment not found');
                }

            } else if ($request->commentable_type == 'blog') {
                $blog = Blog::where('id', $id)->first();
                if (!$blog) {
                    return Response::json(false, 'Blog not found');
                }
            }
            // check reaction

            $check_reaction = Reaction::where([
                'commentable_id' => $id,
                'user_id' => auth('api')->id(),
                'commentable_type' => $request->commentable_type
            ])
                ->first();
            if (!$check_reaction) {
                $reaction = Reaction::create([
                    'user_id' => auth('api')->id(),
                    'commentable_id' => $id,
                    'commentable_type' => $request->commentable_type,
                    'type' => $request->type
                ]);
                return Response::json(true, 'Reaction added successfully', $reaction);
            } else {
                if ($check_reaction->user_id != auth('api')->id()) {
                    return Response::json(false, 'You are not allowed to change this reaction');
                }
                if ($request->type != $check_reaction->type) {
                    $check_reaction->type = $request->type;
                    $check_reaction->save();
                    return Response::json(true, 'Reaction updated successfully', $check_reaction);
                } else {
                    $check_reaction->delete();
                    return Response::json(true, 'Reaction removed successfully');
                }
            }

        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/comments/delete/{id}",
     *      operationId="deleteComment",
     *      tags={"Comment"},
     *      summary="deleteComment",
     *      description="deleteComment",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID : ID Comment",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function delete_comment($id)
    {
        try {
            if (!$id) {
                return Response::json(false, 'Missing comment ID');
            }
            $comment = Comment::where('id', $id)->first();
            if (!$comment) {
                return Response::json(false, 'Comment not found');
            }
            if ($comment->user_id != auth('api')->id()) {
                return Response::json(false, 'You are not allowed to delete this comment');
            }
            $comment->is_deleted = 1;
            $comment->save();
            return Response::json(true, 'Comment deleted successfully');
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/comments/update/{id}",
     *      operationId="update_comment",
     *      tags={"Comment"},
     *      summary="Create a new update_comment",
     *      description="Returns new new update_comment information",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID : ID Comment",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="comment",
     *                     description="Your comment",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function update_comment(Request $request, $id)
    {
        try {
            if (!$id) {
                return Response::json(false, 'Missing comment ID');
            }
            $comment = Comment::where('id', $id)->first();
            if (!$comment) {
                return Response::json(false, 'Comment not found');
            }
            if ($comment->user_id != auth('api')->id()) {
                return Response::json(false, 'You are not allowed to update this comment');
            }
            // validation
            $validator = Validator::make($request->all(), [
                'comment' => 'required',
            ]);
            $comment->comment = $request->comment;
            $comment->updated_at = Carbon::now();
            $comment->save();
            return Response::json(true, 'Comment updated successfully', $comment);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }





}

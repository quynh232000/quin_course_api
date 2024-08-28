<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\Response;
use App\Models\Tag;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Validator;
use Str;

class Blogcontroller extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/blogs/all",
     *      operationId="getblogs",
     *      tags={"Blog"},
     *      summary="Get list blogs",
     *      description="Returns list blogs",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *      @OA\Parameter(
     *         description="",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         )
     *     ),

     * )
     */
    public function all(Request $request)
    {
        try {
            $limit = $request->limit ?? 4;
            $blogs = Blog::where(['is_published' => true, 'is_show' => true,'deleted_at'=>null])->with(['tags.tag', 'user'])->limit($limit)->get();
            // $blogs = Blog::all();
            return Response::json(true, 'Get list blog successfully!', $blogs);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *      path="/api/blogs/{slug}",
     *      operationId="Get blog detail",
     *      tags={"Blog"},
     *      description="Returns blog information",
     *    @OA\Parameter(
     *          name="slug",
     *          description="Blog slug",
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
     * )
     */
    public function detail($slug)
    {
        try {
            if (!$slug) {
                return Response::json(false, 'Missing parameter Slug blog');
            }
            $blog = Blog::where('slug', $slug)->with(['tags.tag', 'user'])->first();
            if (!$blog) {
                return Response::json(false, 'Not found blog with slug: ' . $slug);
            }
            return Response::json(true, 'Get list blog successfully!', $blog);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server...', $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *      path="/api/blogs/my_blogs",
     *      operationId="myblogs",
     *      tags={"Blog"},
     *      description="Returns your list blogs",
     *      @OA\Parameter(
     *         description="",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function my_blogs(Request $request)
    {
        try {
            $limit = $request->limit ?? 10;
            $page = $request->page ?? 1;


            $data = Blog::where(['user_id'=> auth('api')->id(),'deleted_at'=>null])->with('tags.tag')->offset(($page - 1) * $limit)
                ->limit($limit)
                ->get();

            return Response::json(true, 'Get list blog successfully!', $data);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server... ', $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *      path="/api/blogs/{blog_id}/same_author",
     *      operationId="bogsameauthor",
     *      tags={"Blog"},
     *      description="Returns your list blogs",
     *      @OA\Parameter(
     *         description="",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         )
     *     ),
     *    @OA\Parameter(
     *          name="blog_id",
     *          description="bog_id og thif current blog",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function blog_same_author(Request $request, $blog_id)
    {
        try {
            if (!$blog_id) {
                return Response::json(false, 'Missing parameter blog_id');
            }
            $blog = Blog::where('id', $blog_id)->first();
            if (!$blog) {
                return Response::json(false, 'Not found blog with id: ' . $blog_id);
            }
            $limit = $request->limit ?? 10;
            $page = $request->page ?? 1;
            $data = Blog::where(['is_published' => true, 'is_show' => true, 'user_id' => $blog->user_id,'deleted_at'=>null])
                ->with(['tags.tag', 'user'])
                ->offset(($page - 1) * $limit)
                ->limit($limit)
                ->get();
            return Response::json(true, 'Get list blog successfully!', $data);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server... ', $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *      path="/api/blogs/tag/{tag_slug}",
     *      operationId="blog_by_tag_slug",
     *      tags={"Blog"},
     *      description="Returns  list blogs by tag slug",
     *      @OA\Parameter(
     *         description="",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         )
     *     ),
     *       @OA\Parameter(
     *         description="",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="number",
     *         )
     *     ),
     *    @OA\Parameter(
     *          name="tag_slug",
     *          description="bog_id og thif current blog",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function blog_by_tag_slug(Request $request, $tag_slug)
    {
        try {
            if (!$tag_slug) {
                return Response::json(false, 'Missing parameter tag_slug');
            }
            $tag = Tag::where('slug', $tag_slug)->first();
            if (!$tag) {
                return Response::json(false, 'Not found tag with slug: ' . $tag_slug);
            }
            $limit = $request->limit ?? 10;
            $page = $request->page ?? 1;
            $blogs = $tag->blogs()->where(['is_published' => true, 'is_show' => true,'deleted_at'=>null])
                ->with(['tags.tag', 'user'])
                ->offset(($page - 1) * $limit)
                ->limit($limit)
                ->get();
            return Response::json(true, 'Get list blog successfully!', $blogs);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server... ', $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *      path="/api/tags/all",
     *      operationId="alltags",
     *      tags={"Blog"},
     *      summary="Get list Tags",
     *      description="Returns list tags",
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * )
     */
    public function get_all_tags()
    {
        try {
            $data = Tag::all();
            return Response::json(true, 'Get all tags successfully!', $data);
        } catch (Exception $e) {
            return Response::json(false, 'Error from server... ', $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *      path="/api/blogs/create",
     *      operationId="create_blog",
     *      tags={"Blog"},
     *      summary="Create a new blog",
     *      description="Returns new new blog information",
     *     
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title of the new blog",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="content",
     *                     description="Content of the new blog",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="tag_id",
     *                     description="Tag ID of the new blog",
     *                     type="integer"
     *                 ),@OA\Property(
     *                     property="thumbnail_url",
     *                     description="Thumbnail blog ",
     *                    type="file",
     *                     format="file"
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
    public function create_blog(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'title' => 'required|min:10',
                'content' => 'required|min:100',
                'thumbnail_url' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Validation Failed', $validate->errors());
            }
            $user = auth('api')->user();
            $roles = $user->roles()->toArray();
            $from = 'user';
            if (in_array('Teacher', $roles)) {
                $from = 'teacher';
            }
            if ($request->type && $request->type == 'admin' && in_array('Admin', $roles)) {
                $from = 'admin';
            }
            $slug = Str::slug($request->title);
            $checkSlug = Blog::where('slug', $slug)->count();
            if ($checkSlug > 0) {
                $slug = $slug . '-' . time();
            }
            $thumbnail_url = '';
            if ($request->hasFile('thumbnail_url')) {
                $thumbnail_url = Cloudinary::upload($request->file('thumbnail_url')->getRealPath())->getSecurePath();
            }
            $blog = Blog::create([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'thumbnail_url' => $thumbnail_url,
                'user_id' => $user->id,
                'from' => $from,
                'comment_count' => 0,
                'view_count' => 0,
                'is_show' => true,
                'is_published' => false,
                'published_at' => null
            ]);
            if ($request->tag_id && $request->tag_id != '') {
                BlogTag::create([
                    'blog_id' => $blog->id,
                    'tag_id' => $request->tag_id
                ]);
            }
            $blog->tags = $blog->tags();
            return Response::json(true, 'Create blog successfully!', $blog);


        } catch (Exception $e) {
            return Response::json(false, 'Error from server... ', $e->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/blogs/update/{id}",
     *      operationId="update_blog",
     *      tags={"Blog"},
     *      summary="Update a new blog",
     *      description="Returns  blog information",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID of the blog to update",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title of the new blog",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="content",
     *                     description="Content of the new blog",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="tag_id",
     *                     description="Tag ID of the new blog",
     *                     type="integer"
     *                 ),@OA\Property(
     *                     property="thumbnail_url",
     *                     description="Thumbnail blog ",
     *                    type="file",
     *                     format="file"
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
    public function update_blog($id, Request $request)
    {
        try {
            if (!$id) {
                return Response::json(false, 'Missing Blog ID');
            }
            $blog = Blog::where('id', $id)->with('tags.tag')->first();
            if (!$blog) {
                return Response::json(false, 'Not found blog with ID: ' . $id);
            }
            $user = auth('api')->user();
            // check is author blog
            if ($user->id != $blog->user_id) {
                return Response::json(false, 'You are not the author of this blog');
            }
            // validate data
            $validate = Validator::make($request->all(), [
                'title' => 'required|min:10',
                'content' => 'required|min:100',
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Validation Failed', $validate->errors());
            }
            // check has file
            if ($request->hasFile('thumbnail_url')) {
                $blog->thumbnail_url = Cloudinary::upload($request->file('thumbnail_url')->getRealPath())->getSecurePath();
            }
            //  check slug
            if ($request->title != $blog->title) {
                $slug = Str::slug($request->title);
                $checkSlug = Blog::where('slug', $slug)->count();
                if ($checkSlug > 0) {
                    $slug = $slug . '-' . time();
                }
                $blog->title = $request->title;
                $blog->slug = $slug;
            }
            // cheeck add tag
            if ($request->tag_id && $request->tag_id != '') {
                BlogTag::create([
                    'blog_id' => $blog->id,
                    'tag_id' => $request->tag_id
                ]);
            }
            $blog->save();
            return Response::json(true, 'Update blog successfully!', $blog);


        } catch (Exception $e) {
            return Response::json(false, 'Error from server... ', $e->getMessage());
        }
    }


    /**
 * @OA\Post(
 *      path="/api/blogs/{blog_id}/delete_tag_blog/{tag_id}",
 *      operationId="delete_tag_blog",
 *      tags={"Blog"},
 *      summary="Delete tag blog",
 *      description="Returns  blog information",
 *      @OA\Parameter(
 *          name="blog_id",
 *          description="ID of the blog ",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *       @OA\Parameter(
 *          name="tag_id",
 *          description="ID of the tag",
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
    public function delete_tag_blog(Request $request, $blog_id, $tag_id)
    {
        try {
            if (!$blog_id || !$tag_id) {
                return Response::json(false, 'Missing Blog ID or Tag ID');
            }
            $blog = Blog::where('id', $blog_id)->with('tags.tag')->first();
            if (!$blog) {
                return Response::json(false, 'Not found blog with ID: ' . $blog_id);
            }
            $tag = Tag::find($tag_id);
            if (!$tag) {
                return Response::json(false, 'Not found tag with ID: ' . $tag_id);
            }
            $user = auth('api')->user();
            // check is author blog
            if ($user->id != $blog->user_id) {
                return Response::json(false, 'You are not the author of this blog');
            }
           
            BlogTag::where('blog_id', $blog_id)->where('tag_id', $tag_id)->delete();
            return Response::json(true, 'Delete blog successfully!');
        } catch (Exception $e) {
            return Response::json(false, 'Error from server... ', $e->getMessage());
        }
    }
     /**
 * @OA\Post(
 *      path="/api/blogs/delete_blog/{blog_id}",
 *      operationId="delete_blog",
 *      tags={"Blog"},
 *      summary="Delete  blog",
 *      description="Returns  blog information",
 *      @OA\Parameter(
 *          name="blog_id",
 *          description="ID of the blog ",
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
public function delete_blog(Request $request, $blog_id)
{
    try {
        if (!$blog_id ) {
            return Response::json(false, 'Missing Blog ID ');
        }
        $blog = Blog::where('id', $blog_id)->with('tags.tag')->first();
        if (!$blog) {
            return Response::json(false, 'Not found blog with ID: ' . $blog_id);
        }
        $user = auth('api')->user();
        // check is author blog
        if ($user->id != $blog->user_id) {
            return Response::json(false, 'You are not the author of this blog');
        }
        $blog->deleted_at = Carbon::now();
        $blog->save();
        return Response::json(true, 'Delete blog successfully!',$blog);
    } catch (Exception $e) {
        return Response::json(false, 'Error from server... ', $e->getMessage());
    }
}


}
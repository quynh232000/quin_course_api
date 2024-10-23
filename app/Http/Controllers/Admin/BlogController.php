<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\Tag;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;
class BlogController extends Controller
{
    public function index()
    {
        $data = Blog::all();
        return view('pages.blog.listblogs', compact('data'));

    }
    public function create()
    {
        $tags = Tag::all();
        return view('pages.blog.create_blog', compact('tags'));
    }
    public function update($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Blog Id is required');
        }
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->back()->with('error', 'Blog not found');
        }
        $tags = Tag::all();
        return view('pages.blog.create_blog', compact('blog','tags'));
    }

    public function _create(Request $request, $id = null)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', "Please enter all required fields");
        }
        if ($id == null) {
            if (!$request->hasFile('thumbnail_url')) {
                return redirect()->back()->withInput()->with('error', "Please upload image file");
            }
            $thumbnail_url = Cloudinary::upload($request->file('thumbnail_url')->getRealPath())->getSecurePath();

            $slug = Str::slug($request->title);
            $checkSlug = Blog::where('slug', $slug)->count();
            if ($checkSlug > 0) {
                $slug = $slug . "-" . $checkSlug;
            }
            $blog = Blog::create([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'content' => $request->input('content'),
                'slug' => $slug,
                'from' => 'admin',
                'user_id' => auth('admin')->user()->id,
                'thumbnail_url' => $thumbnail_url,
                'is_published' => $request->is_published ? true : false,
                'is_show' => $request->is_show ? true : false,
                'published_at' => $request->is_published ? Carbon::now() : null,
                'comment_count' => 0,
                'view_count' => 0
            ]);
            if ($request->tag && $request->tag != '') {
                BlogTag::create([
                    'tag_id' => $request->tag,
                    'blog_id' => $blog->id
                ]);
            }



            return redirect()->back()->with('success', 'Create new blog successfully');

        } else {
            $blog = Blog::find($id);
            if (!$blog) {
                return redirect()->back()->with('error', 'Blog not found');
            }
            if ($request->hasFile('thumbnail_url')) {
                $thumbnail_url = Cloudinary::upload($request->file('thumbnail_url')->getRealPath())->getSecurePath();
                $blog->thumbnail_url = $thumbnail_url;
            }

            // check tag 
            if ($request->tag && $request->tag != '') {
                $tag = BlogTag::where(['blog_id' => $id, 'tag_id' => $request->tag])->first();
                if (!$tag) {
                    BlogTag::create([
                        'tag_id' => $request->tag,
                        'blog_id' => $id
                    ]);
                }
            }

            $blog->title = $request->title;
            $blog->subtitle = $request->subtitle;
            $blog->content = $request->input('content');


            $blog->is_published = $request->is_published && $request->is_published == 'on' ? true : false;
            $blog->is_show = $request->is_show ? true : false;
            $blog->save();
            return redirect()->back()->with('success', 'Update blog information successfully');

        }
    }
    public function delete($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Blog Id is required');
        }
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->back()->with('error', 'Blog not found');
        }
        $blog->delete();
        return redirect()->back()->with('success', 'Delete blog successfully');
    }
    public function deletetag($id, $tag_id){
        if (!$id ||!$tag_id) {
            return redirect()->back()->with('error', 'Blog Id or Tag Id is required');
        }
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect()->back()->with('error', 'Blog not found');
        }
        $tag = BlogTag::where(['id' => $tag_id])->first();
        if($tag){
            $tag->delete();
            return redirect()->back()->with('success', 'Delete tag successfully');
        }
        return redirect()->back()->with('error', 'Tag not found for this blog');
    }
}

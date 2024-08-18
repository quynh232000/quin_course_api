<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LevelCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;
class LevelController extends Controller
{
    public function index()
    {
        $data = LevelCourse::all();
        return view('pages.levels', compact('data'));
    }
    public function get_category_info($id)
    {
        $category = LevelCourse::find($id);

        if (!$category) {
            return redirect('levels')->with("error", "LevelCourse {$id} not found");
        }
        $category->parents = $category->getAllParents();

        $data = LevelCourse::where('parent_id', $id)->get()->map(function ($category) {
            $category->haschild = $category->hasChild();
            return $category;
        });
        return view('pages.levels', compact('data', 'category'));
    }
    public function create(Request $request, $id = null)
    {

        $validetor = Validator::make($request->all(), [
            'name' => "required|string",
            'description' => "required|string",
        ]);
        if ($validetor->fails()) {
            return redirect()->back()->with("error", "Name and description is required");
        }
        $slug = Str::slug($request->name);
        if ($id) {
            $level = LevelCourse::find($id);
            if (!$level) {
                return redirect()->back()->with("error_update", "LevelCourse {$id} not found");
            }
            $level->name = $request->name;
            $level->description = $request->description;
            $level->slug = $slug;
            $level->save();
            return redirect()->back()->with("success", "Update LevelCourse successfully!");

        } else {
            $level = LevelCourse::create([
                'name' => $request->name,
                'description' => $request->description,
                'slug' => $slug,
            ]);
            return redirect()->back()->with("success", "Create new category successfully!");
        }



    }
    public function update(Request $request, $id)
    {
        if (!$request->name && !$request->icon) {
            return redirect()->back()->with("error_update", "Name and Icon file are required");
        }
        $category = LevelCourse::find($id);
        if (!$category) {
            return redirect()->back()->with("error_update", "LevelCourse {$id} not found");
        }
        if ($request->name && $request->name != $category->name) {
            $category->name = $request->name;
            $slug = Str::slug($request->name);
            $checkSlug = LevelCourse::where('slug', $slug)->count();
            if ($checkSlug > 1) {
                $category->slug = $slug . "-" . $category->id;
            }
        }
        if ($request->icon) {
            $icon_url = Cloudinary::upload($request->file('icon')->getRealPath())->getSecurePath();
            $category->icon_url = $icon_url;
        }

        $category->save();

        return redirect()->back()->with("success_update", "Update category successfully!");
    }
    public function delete($id)
    {
        if (!$id) {
            return redirect()->back()->with("error_delete", "LevelCourse id is required");
        }
        $level = LevelCourse::find($id);
        if (!$level) {
            return redirect()->back()->with("error_delete", "LevelCourse {$id} not found");
        }
        
       
        $level->delete();
        return redirect()->back()->with("success_delete", "Delete level successfully!");
        ;
    }
}

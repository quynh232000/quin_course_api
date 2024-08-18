<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $data = Category::where('parent_id', 0)->get()->map(function ($category) {
            $category->haschild = $category->hasChild();
            return $category;
        });
        return view('pages.categories', compact('data'));
    }
    public function get_category_info($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return redirect('categories')->with("error", "Category {$id} not found");
        }
        $category->parents = $category->getAllParents();

        $data = Category::where('parent_id', $id)->get()->map(function ($category) {
            $category->haschild = $category->hasChild();
            return $category;
        });
        return view('pages.categories', compact('data', 'category'));
    }
    public function create(Request $request, $id = null)
    {
        if ($id) {
            $validetor = Validator::make($request->all(), [
                'name' => "required|string"
            ]);
            if ($validetor->fails()) {
                return redirect()->back()->with("error", "Name is required");
            }
        } else {

            $validetor = Validator::make($request->all(), [
                'name' => "required|string",
                "icon" => "required|file"
            ]);
            if ($validetor->fails()) {
                return redirect()->back()->with("error", "Name and Icon file are required");
            }
        }

        $icon_url = "";
        if ($request->hasFile('icon')) {
            $icon_url = Cloudinary::upload($request->file('icon')->getRealPath())->getSecurePath();
        }
        $slug = Str::slug($request->name);
        $category = Category::create([
            'name' => $request->name,
            'icon_url' => $icon_url,
            'slug' => $slug,
        ]);
        if ($id) {
            $category->parent_id = $id;
        }

        $checkSlug = Category::where('slug', $slug)->count();
        if ($checkSlug > 1) {
            $category->slug = $slug . "-" . $category->id;
        }

        $category->save();

        return redirect()->back()->with("success", "Create new category successfully!");
    }
    public function update(Request $request, $id)
    {
        if (!$request->name && !$request->icon) {
            return redirect()->back()->with("error_update", "Name and Icon file are required");
        }
        $category = Category::find($id);
        if (!$category) {
            return redirect()->back()->with("error_update", "Category {$id} not found");
        }
        if ($request->name && $request->name != $category->name) {
            $category->name = $request->name;
            $slug = Str::slug($request->name);
            $checkSlug = Category::where('slug', $slug)->count();
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
        if(!$id){
            return redirect()->back()->with("error_delete", "Category id is required");
        }
        $category = Category::find($id);
        if (!$category) {
            return redirect()->back()->with("error_delete", "Category {$id} not found");
        }
        $checkChild = Category::where('parent_id', $id)->count();
        if ($checkChild > 0) {
            return redirect()->back()->with("error_delete", "Failed to delete category. Category '$category->name' has $checkChild children!");
        }
        $category->delete();
        return redirect()->back()->with("success_delete", "Delete category successfully!");;
    }
}

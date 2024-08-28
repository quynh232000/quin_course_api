<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;
class TagController extends Controller
{
    public function index($id = null)
    {
        $data = Tag::all();
        if ($id) {
            $tag = Tag::find($id);
            return view("pages.tags", compact('data', 'tag'));
        }
        return view("pages.tags", compact('data'));

    }
    public function create(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()->with('error', 'Required parameters name');
        }
        if ($id) {
            $tag = Tag::find($id);
            if ($tag->name != $request->name) {
                $slug = Str::slug($request->name);
                $checkSlug = Tag::where('slug', $slug)->count(); //
                if ($checkSlug > 0) {
                    $slug = $slug . '_' . $checkSlug;
                }
                $tag->update([
                    'name' => $request->name,
                    'slug' => $slug,
                ]);
            }
            return redirect()->back()->with('success', 'Update Tag successfully!');
        } else {
            $slug = Str::slug($request->name);
            $checkSlug = Tag::where('slug', $slug)->count(); //
            if ($checkSlug > 0) {
                $slug = $slug . '_' . $checkSlug;
            }
            Tag::create([
                'name' => $request->name,
                'slug' => $slug,
            ]);
            return redirect()->back()->with('success', 'Create new Tag successfully!');
        }
    }
    public function delete($id)
    {
        if (!$id) {
            return redirect('/tags')->withInput()->with('message', 'Tag ID is required');
        }

        $tag = Tag::find($id);
        if (!$tag) {
            return redirect('/tags')->withInput()->with('message', "Tag ID {$id} not found");
        }
        $tag->delete();
        return redirect('/tags')->with('success', 'Delete Tag successfully!');
    }
}

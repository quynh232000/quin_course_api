<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('pages.banner.listbanners', compact('banners'));

    }
    public function create()
    {
        return view('pages.banner.create_banner');
    }
    public function update($id)
    {
        if (!$id) {
            return redirect()->back()->with('error', 'Banner Id is required');
        }
        $banner = Banner::find($id);
        if (!$banner) {
            return redirect()->back()->with('error', 'Banner not found');
        }
        return view('pages.banner.create_banner', compact('banner'));
    }

    public function _create(Request $request, $id = null)
    {
        // dd($request->all());
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'alt' => 'required',
            'description' => 'required',
            'placement' => 'required',
            'link_to' => 'required',
            'type' => 'required',
            'priority' => 'required',
            'expired_at' => 'required',
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withInput()->with('error', "Please enter all required fields");
        }
        if ($id == null) {
            if (!$request->hasFile('image')) {
                return redirect()->back()->withInput()->with('error', "Please upload image file");
            }
            $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

            Banner::create([
                'title' => $request->title,
                'description' => $request->description,
                'alt' => $request->alt,
                'from' => 'admin',
                'user_id' => auth('admin')->user()->id,
                'placement' => $request->placement,
                'link_to' => $request->link_to,
                'banner_url' => $image_url,
                'type' => $request->type,
                'priority' => $request->priority,
                'is_blank' => $request->is_blank ? true : false,
                'is_show' => $request->is_show ? true : false,
                'expired_at' => $request->expired_at,
            ]);
            return redirect()->back()->with('success', 'Create new banner successfully');

        } else {
            $banner = Banner::find($id);
            if (!$banner) {
                return redirect()->back()->with('error', 'Banner not found');
            }
            if ($request->hasFile('image')) {
                $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
                $banner->banner_url = $image_url;
            }
            $banner->title = $request->title;
            $banner->description = $request->description;
            $banner->alt = $request->alt;
            $banner->placement = $request->placement;
            $banner->link_to = $request->link_to;
            $banner->type = $request->type;
            $banner->priority = $request->priority;
            $banner->is_blank = $request->is_blank  ? true : false;
            $banner->is_show = $request->is_show ? true : false;
            $banner->expired_at = $request->expired_at;
            $banner->save();
            return redirect()->back()->with('success', 'Update banner information successfully');

        }
    }
    public function delete($id){
        if (!$id) {
            return redirect()->back()->with('error', 'Banner Id is required');
        }
        $banner = Banner::find($id);
        if (!$banner) {
            return redirect()->back()->with('error', 'Banner not found');
        }
        $banner->delete();
        return redirect()->back()->with('success', 'Delete banner successfully');
    }
}

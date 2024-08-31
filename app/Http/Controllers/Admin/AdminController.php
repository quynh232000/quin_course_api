<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data['user_count'] = User::count();
        $data['course_count'] = Course::count();
        $data['money_total'] = Order::where('status','completed')->sum('total');
        return view("pages.dashboad",compact('data'));
    }


    public function settings($id = null)
    {
        $data = Setting::all();
        if ($id) {
            $setting = Setting::find($id);
            return view("pages.settings.general", compact('data', 'setting'));
        }
        return view("pages.settings.general", compact('data'));

    }
    public function settings_create(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'key' => 'required',
            'value' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withInput()->with('error', 'Required parameters name');
        }
        if ($id) {
            $setting = Setting::find($id);
            $setting->update([
                'type' => $request->type,
                'key' => $request->key,
                'value' => $request->value,
            ]);

            return redirect()->back()->with('success', 'Update Setting successfully!');
        } else {

            Setting::create([
                'type' => $request->type,
                'key' => $request->key,
                'value' => $request->value,
                'user_id' => auth('admin')->user()->id,
            ]);
            return redirect()->back()->with('success', 'Create new Setting successfully!');
        }
    }
    public function delete($id)
    {
        if (!$id) {
            return redirect('/settings')->withInput()->with('message', 'Setting ID is required');
        }

        $setting = Setting::find($id);
        if (!$setting) {
            return redirect('/settings')->withInput()->with('message', "Setting ID {$id} not found");
        }
        $setting->delete();
        return redirect('/settings')->with('success', 'Delete Setting successfully!');
    }

}

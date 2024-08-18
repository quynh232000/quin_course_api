<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->limit(20)->get()->map(function ($user) {
            $user->roles = $user->roles();
            return $user;
        });
        return view("pages.user.listuser", compact("users"));
    }
    public function userdetail($uuid)
    {

        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }
        $roles = UserRole::where('user_id', $user->id)->with('role')->get();
        $allRoles = Role::all();
        return view("pages.user.detail", compact("user", "roles", "allRoles"));
    }
    public function updateStatus($uuid)
    {
        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }
        if ($user->blocked_until) {
            $user->blocked_until = null;
            $user->failed_attempts = 0;
            $mess = "User unblocked";
        } else {
            $user->blocked_until = Carbon::now()->addDay();
            $mess = "User blocked";
        }
        $user->save();
        return redirect()->back()->with("success", $mess);
    }
    public function iscomment($uuid)
    {
        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }

        if ($user->is_comment_blocked) {
            $user->is_comment_blocked = 0;
            $mess = "User unblocked for comment";
            $user->comment_blocked_at = null;

        } else {
            $user->is_comment_blocked = 1;
            $user->comment_blocked_at = Carbon::now();
            $mess = "User blocked for comment";
        }

        $user->save();
        return redirect()->back()->with("success", $mess);
    }
    public function changeavatar($uuid, Request $request)
    {
        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }
        $file = $request->file('avatar_url');
        if (!$file) {
            return redirect()->back()->with("error", "Avatar file is required");
        }


        if ($request->hasFile('avatar_url')) {
            $avatar = Cloudinary::upload($request->file('avatar_url')->getRealPath())->getSecurePath();
            $user->avatar_url = $avatar;
        }


        $user->save();
        return redirect()->back()->with("success", 'Avatar changed successfully');
    }
    public function changethumbnail($uuid, Request $request)
    {
        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }
        $file = $request->file('thumbnail_url');
        if (!$file) {
            return redirect()->back()->with("error", "Thumnail file is required");
        }


        if ($request->hasFile('thumbnail_url')) {
            $thumbnail_url = Cloudinary::upload($request->file('thumbnail_url')->getRealPath())->getSecurePath();
            $user->thumbnail_url = $thumbnail_url;
        }


        $user->save();
        return redirect()->back()->with("success", 'Thumbnail changed successfully');
    }
    public function addRole($uuid, Request $request)
    {
        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }
        $role = $request->role;
        if (!$role) {
            return redirect()->back()->with("error", "Role is required");
        }
        $checkUserRole = UserRole::where('user_id', $user->id)->where('role_id', $role)->first();
        if (!$checkUserRole) {
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => $role,
            ]);
            $mess = "Role added successfully";
        } else {
            $mess = "Role already assigned";
        }

        return redirect()->back()->with("success", $mess);
    }
    public function deleteRole($uuid, $id)
    {
        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }

        $checkUserRole = UserRole::where(['user_id' => $user->id, 'role_id' => $id])->first();
        if (!$checkUserRole) {
            return redirect()->back()->with("error", "Role not assigned to this user");
        } else {
            $checkUserRole->delete();
            $mess = "Role deleted successfully";

        }

        return redirect()->back()->with("success", $mess);
    }
    public function updateInfo($uuid, Request $request)
    {
        $user = User::where("uuid", $uuid)->first();
        if (!$user) {
            return redirect()->back()->with("error", "User not found");
        }
        $message = 'Update User Information successfully!';
        if ($request->username && $request->username != "" && $request->username != $user->username) {
            $checkUser = User::where('username', $request->username)->first();
            if ($checkUser) {
                $message = "Username already exists!";
            } else {
                if (count(explode(' ', $request->username)) > 1) {
                    $message = "Invalid username! Username should not contain space.";
                } else {
                    $user->username = strtolower($request->username);

                }
            }
        }
        // update first_name, last_name, full_name
        $full_name = $user->full_name;
        $firt_name = $user->firt_name;
        $last_name = $user->last_name;
        if ($request->first_name && $request->first_name != "" && $request->first_name != $user->first_name) {
            $firt_name = $request->first_name;
            $user->first_name = $request->first_name;
        }
        if ($request->last_name && $request->last_name != "" && $request->last_name != $user->last_name) {
            $last_name = $request->last_name;
            $user->last_name = $request->last_name;
        }
        $full_name = $firt_name . ' ' . $last_name;
        $user->full_name = $full_name;
        // update addres 
        if ($request->address && $request->address != '' && $request->address != $user->address) {
            $user->address = $request->address;
        }
        // update birthday
        if ($request->birthday && $request->birthday != '' && $request->birthday != $user->birthday) {
            $user->birthday = $request->birthday;
        }

        // update phone_number
        if ($request->phone_number && $request->phone_number != "" && $request->phone_number != $user->phone_number) {
            if (preg_match('/^(03|05|07|08|09)\d{8}$/', $request->phone_number)) {

                $user->phone_number = $request->phone_number;
            } else {
                $message = "Invalid phone number.";
            }
        }
        // update bio 
        if ($request->bio && $request->bio != '' && $request->bio != $user->bio) {
            if (strlen($request->bio) > 150) {
                $message = "Maximum 150 leters";
            } else {
                $user->bio = $request->bio;

            }
        }

        $user->save();
        return redirect()->back()->with("success", $message);
    }
}

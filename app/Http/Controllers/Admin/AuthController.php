<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login()
    {
        return view("pages.login");
    }
    public function _login(Request $request)
    {
        if (!$request->email) {
            return redirect()->back()->withInput()->with('error', 'Email is required');
        }
        if (!$request->password) {
            return redirect()->back()->withInput()->with('error', 'Password is required');
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'User not found!');
        }
        if (!\Hash::check($request->password, $user->password)) {
            return redirect()->back()->withInput()->with('error', 'Password is incorrect!');
        }
        if ($user->blocked_until) {
            return redirect()->back()->withInput()->with('error', 'Your account is blocked. Please contact with administrator.');
        }
        auth('admin')->login($user);
        $redirect_url = session('redirect_url') ?? '/';
        session()->forget('redirect_url');
        return redirect($redirect_url);
    }
    public function logout()
    {
        auth('admin')->logout();
        return redirect('/auth/login');
    }
}

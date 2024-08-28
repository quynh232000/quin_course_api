<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Str;
use App\Services\TransactionService;

class AuthController extends Controller
{
    public function login()
    {
        $trans = new TransactionService();
        $trans->fetchTransactionHistory();
        if(auth('admin')->check()){
            return redirect('/');
        }
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
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function callback($provider)
    {
        $data = Socialite::driver($provider)->user();
        $email = $data->user['email'];
        $checkUser = User::where('email', $email)->first();
        if ($checkUser) {
            auth('admin')->login($checkUser);
        } else {
            if ($provider == 'google') {
                $avatar = $data->user['picture'];
                $first_name = $data->user['family_name'];
                $last_name = $data->user['given_name'];
                $username = explode('@', $data->user['email'])[0];
            } else {
                $avatar = $data->user['avatar_url'];
                $first_name = '';
                $last_name = $data->user['name'];
                $username = $data->nickname;

            }
            $newUser = User::create([
                'uuid'=>Str::uuid(),
                'email' => $email,
                'full_name' => $data->user['name'],
                'avatar_url' => $avatar,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'username' => $username,
            ]);
            UserRole::create([
                'user_id' => $newUser->id,
                'role_id' => 1,
            ]);
            auth('admin')->login($newUser);
        }

        $redirect_url = session('redirect_url') ?? '/';
        session()->forget('redirect_url');
        return redirect($redirect_url);


    }
}

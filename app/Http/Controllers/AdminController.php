<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\News;
use App\Models\Order;
use App\Models\ProcessTour;
use App\Models\Product;
use App\Models\Province;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Hash;
use Str;

class AdminController extends Controller
{

  
    public function login_(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors())->withInput()->with('errorMess', 'Vui lòng nhập đầy đủ thông tin!');
        }
        $email = $request->email;
        $password = $request->password;
        $user = User::where('email', $email)->first();


        if ($user) {
            $credentials = $request->only('email', 'password');
            if (auth('web')->attempt($credentials)) {

                return redirect()->intended('/');
            } else {
                return redirect()->back()->withInput()->with('errorMess', 'Mật khẩu không chính xác!');
            }

        } else {
            return redirect()->back()->withInput()->with('errorMess', 'Email đăng nhập không đúng!');

        }


    }

}

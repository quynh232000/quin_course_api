<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Province;
use App\Models\Response;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Str;
use Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

    /**
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="register",
     *      tags={"Users"},
     *      summary="Register a user",
     *      description="Returns new user",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="full_name",
     *                     description="Your full name",
     *                     type="string",
     *                      example="Nguyen van a"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Your email address",
     *                     type="string",
     *                      example="test@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Your password",
     *                     type="string"
     *                 ),
     *                @OA\Property(
     *                     property="password_confirmation",
     *                     description="confirm password",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);



        if ($validator->fails()) {
            return Response::json(false, 'Missing parameters', $validator->errors());
        }

        $user = User::create([
            'avatar' => "https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg",
            'uuid' => Str::uuid(),
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = auth('api')->login($user);
        return Response::json(true, 'Register successful', $user, $this->respondWithToken($token));
    }
    /**
     * @OA\Post(
     *      path="/api/auth/update_profile",
     *      operationId="update_profile",
     *      tags={"Users"},
     *      summary="Update your profile information",
     *      description="Returns new profile information",
     *     
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="full_name",
     *                     description="Your full name",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="address",
     *                     description="Your address",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="phone_number",
     *                     description="Your phone number ",
     *                     type="integer"
     *                 ),@OA\Property(
     *                     property="avatar",
     *                     description="Your avatar ",
     *                    type="file",
     *                     format="file"
     *                 ),
     *                 
     *                
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function update_profile(Request $request)
    {
        try {

            $user = User::find(auth('api')->id());

            if (!$user) {
                return Response::json(false, 'User not found');
            }

            if ($request->full_name) {
                $user->full_name = $request->full_name;
            }
            if ($request->address) {
                $user->address = $request->address;
            }
            if ($request->phone_number) {
                $user->phone_number = $request->phone_number;
            }
            if ($request->address) {
                $user->address = $request->address;
            }
            if ($request->hasFile('avatar')) {
                $avatar = Cloudinary::upload($request->file('avatar')->getRealPath())->getSecurePath();
                // $avatar = $request->file('avatar')->store('profile', 'public');
                $user->avatar = $avatar;
            }
            $user->save();
            return Response::json(true, 'Profile updated successfully', $user);


        } catch (Exception $e) {
            return Response::json(false, 'An error occurred while updating profile ', $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *      path="/api/auth/change_password",
     *      operationId="change_password",
     *      tags={"Users"},
     *      summary="Change password for current",
     *      description="Returns status of the change password",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="current_password",
     *                     description="Your current password",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="new_password",
     *                     description="Your new password",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="new_password_confirmation",
     *                     description="Your new_password_confirmation ",
     *                     type="string"
     *                 ),
     *                 required={"current_password","new_password","new_password_confirmation"}
     *                
     *             )
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function change_password(Request $request)
    {
        try {

            $user = User::find(auth('api')->id());
            if (!$user) {
                return Response::json(false, 'User not found');
            }


            $validate = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'new_password' => 'required|string',
                'new_password_confirmation' => 'required|string',
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Missing parameters', $validate->errors());
            }

            if ($request->current_password && Hash::check($request->current_password, $user->password)) {
                if ($request->new_password && $request->new_password_confirmation && $request->new_password == $request->new_password_confirmation) {
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                    return Response::json(true, 'Password changed successfully');
                } else {
                    return Response::json(false, 'New password and confirmation do not match');
                }
            } else {
                return Response::json(false, 'Current password is incorrect');
            }

        } catch (Exception $e) {
            return Response::json(false, 'An error occurred while changing password ', $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="login",
     *      tags={"Users"},
     *      summary="Login",
     *      description="Returns user",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     * 
     *                     property="email",
     *                     description="Your email address",
     *                     type="string",
     *                    example="test@gmail.com"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="Your password",
     *                     type="string",
     *                     example="123456"
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return Response::json(false, 'Missing parameters', $validator->errors());
        }
        $user = User::where('email', $request->email)->get();
        if (count($user) == 0) {
            return Response::json(false, 'Email không tồn tại trên hệ thống!', $validator->errors());
        }
        if (!$token = auth('api')->attempt($validator->validated())) {
            return Response::json(false, 'Mật khẩu không đúng!', $validator->errors());
        }
        return Response::json(true, 'Login successfully!', auth('api')->user(), $this->respondWithToken($token));
    }




    /**
     * @OA\Get(
    
     * 
     *      path="/api/me",
     *      operationId="getme",
     *      tags={"Users"},
     *      summary="Get User Information",
     *      description="Returns user information",
     *      @OA\Header(
     *         header="Authorization",
     *         description="Api key header",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      security={{
     *         "bearer": {}
     *     }},
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */
    public function me()
    {

        $user = auth()->user();
        if ($user == null)
            return Response::json(false, "Unauthorized");
        return Response::json(true, 'Success', auth()->user());
    }



    /**
         * @OA\Post(
        
         * 
         *      path="/api/logout",
         *      operationId="logout",
         *      tags={"Users"},
         *      summary="Logout account",
         *      description="Returns status",
         *      @OA\Header(
         *         header="Authorization",
         *         description="Api key header",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      security={{
         *         "bearer": {}
         *     }},
         *     @OA\Response(
         *         response=400,
         *         description="Invalid ID supplied"
         *     ),
         * 
         *     
         * )
         */
    public function logout()
    {
        auth()->logout();

        return Response::json(true, 'Successfully logged out');
    }

    /**
         * @OA\Post(
        
         * 
         *      path="/api/refresh",
         *      operationId="refresh",
         *      tags={"Users"},
         *      summary="Refresh token user",
         *      description="Returns status",
         *      @OA\Header(
         *         header="Authorization",
         *         description="Api key header",
         *         required=true,
         *         @OA\Schema(
         *             type="string"
         *         )
         *     ),
         *      security={{
         *         "bearer": {}
         *     }},
         *     @OA\Response(
         *         response=400,
         *         description="Invalid ID supplied"
         *     ),
         * 
         *     
         * )
         */
    public function refresh()
    {
        $token = $this->respondWithToken(auth()->refresh());
        return Response::json(true, 'Refreshing token', auth('api')->user(), $this->respondWithToken(auth('api')->refresh()));
    }


    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }
    /**
        * @OA\Get(
       
        * 
        *      path="/api/get_country",
        *      operationId="get country",
        *      tags={"Location"},
        *      summary="Get all Country",
        *      description="Returns list countries",
        *   
        *     @OA\Response(
        *         response=400,
        *         description="Invalid ID supplied"
        *     ),
        * 
        *     
        * )
        */
    public function get_city()
    {
        $cities = Country::all();
        return Response::json(true, 'Get countries successfully!', $cities);
    }
    /**
    * @OA\Get(
   
    * 
    *      path="/api/get_province",
    *      operationId="get get_province",
    *      tags={"Location"},
    *      summary="Get all get_province",
    *      description="Returns list get_province",
    *   
    *     @OA\Response(
    *         response=400,
    *         description="Invalid ID supplied"
    *     ),
    * 
    *     
    * )
    */
    public function get_province()
    {
        $provinces = Province::all();
        return Response::json(true, 'Get provinces successfully!', $provinces);
    }


    /**
     * @OA\Post(
     *      path="/api/auth/withgoogle",
     *      operationId="withgoogle",
     *      tags={"Users"},
     *      summary="Login with google",
     *      description="Returns user",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id_token",
     *                     description="Your ID token, used to identify",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */

    public function googleAuthentication(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_token' => 'required|string',
            ]);
            if ($validator->fails()) {
                return Response::json(false, 'Missing parameters id_token', $validator->errors());
            }
            $token = ($request->id_token);

            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return Response::json(false, "Invalid token");
            }
            $payload = $parts[1];
            $decodedPayload = base64_decode($payload);
            if ($decodedPayload === false) {
                return Response::json(false, "Base64 decode failed");

            }
            $data = json_decode($decodedPayload, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return Response::json(false, "JSON decode failed: " . json_last_error_msg());

            }
            $user = User::where('email', $data['email'])->first();
            // return $data;
            if ($user) {
                $token = auth('api')->login($user);
                return Response::json(true, 'Đăng nhập thành công!', auth('api')->user(), $this->respondWithToken($token));
            } else {
                $user = new User();
                $user->uuid = Str::uuid();
                $user->full_name = $data['name'];
                $user->email = $data['email'];
                $user->avatar = $data['picture'];
                $user->password = Hash::make(Str::random(16));
                $user->save();
                $token = auth('api')->login($user);
                return Response::json(true, 'Đăng kí thành công!', auth('api')->user(), $this->respondWithToken($token));
            }

        } catch (Exception $e) {
            return Response::json(false, "Error: " . $e->getMessage());
        }



    }


    /**
     * @OA\Post(
     *      path="/api/auth/forgotpassword",
     *      operationId="forgotpassword",
     *      tags={"Users"},
     *      summary="forgotpassword",
     *      description="forgotpassword",
     *     
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="Your email",
     *                     type="string",
     *                 ),
     *             )
     *         )
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */

    public function forgotpassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string',
            ]);
            if ($validator->fails()) {
                return Response::json(false, 'Vui lòng nhập Email', $validator->errors());
            }
            $user = User::where(['email' => $request->email])->first();
            if (!$user) {
                return Response::json(false, 'Email không tồn tại trên hệ thống');
            }

            $token = Str::random(60);
            $user->remember_token = $token;
            $user->save();

            // send mail 

            $url = "http://localhost:4200/changepassword/" . $token;

            $data['title'] = "Xác nhận thay đổi mật khẩu mới tại Quin Travel";
            $data['url'] = $url;
            $data['user'] = $user;
            $data['email'] = $user->email;

            Mail::send("mailchangepassword", ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            return Response::json(true, "Vui lòng kiểm tra email để xác nhận thay đổi mật khẩu!");


        } catch (Exception $e) {
            return Response::json(false, "Error: " . $e->getMessage());
        }



    }

    /**
     * @OA\Post(
     *      path="/api/auth/changepassword",
     *      operationId="changepassword",
     *      tags={"Users"},
     *      summary="changepassword",
     *      description="changepassword",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="password",
     *                     description="Your password",
     *                     type="string",
     *                 ),
     *             ),
     *              @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     description="Your token",
     *                     type="string",
     *                 )
     *             )
     *         )
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function changepassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'password' => 'required|string',
                'token' => 'required|string',
            ]);
            if ($validator->fails()) {
                return Response::json(false, 'Vui lòng nhập đầy đủ thông tin', $validator->errors());
            }
            $user = User::where(['remember_token' => $request->token])->first();
            if (!$user) {
                return Response::json(false, 'Token không hợp lệ hoặc đã hết hạn');
            }
            $user->password = Hash::make($request->password);
            $user->remember_token = null;
            $user->save();
            return Response::json(true, "Đặt mật khẩu mới thành công!");


        } catch (Exception $e) {
            return Response::json(false, "Error: " . $e->getMessage());
        }



    }

}
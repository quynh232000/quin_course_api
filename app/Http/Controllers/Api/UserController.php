<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\PasswordResetToken;
use App\Models\Response;
use App\Models\Role;
use App\Models\User;
use App\Models\Usersocial;
use App\Models\UserRole;
use Carbon\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Str;
use Hash;
use DB;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
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
     *                     property="first_name",
     *                     description="Your first name",
     *                     type="string",
     *                      example="Nguyen van "
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     description="Your last name",
     *                     type="string",
     *                      example="An "
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
     *                     property="code",
     *                     description="Code verify your email",
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
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'code' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return Response::json(false, 'Missing parameters', $validator->errors());
            }
            // check full_name has to words 
            // $words = explode(' ', $request->full_name);
            // if (count($words) < 2) {
            //     return Response::json(false, 'Full name must have at least two words');
            // }
            // check email is exists in database 
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return Response::json(false, 'Email already exists');
            }
            // check code is correct
            $checkCode = PasswordResetToken::where(['token' => $request->code, 'email' => $request->email])->first();
            if (!$checkCode) {
                return Response::json(false, 'Code is not correct');
            }
            $username = explode('@', $request->email)[0];
            $checkUsername = User::where('username', $username)->count();
            if ($checkUsername) {
                $username = $$username . ($checkUsername + 1);
            }
            // create new user and login
            $first_name = $request->first_name;

            $list_avatars = [
                'https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436188.jpg',
                'https://img.freepik.com/free-psd/3d-illustration-bald-person-with-glasses_23-2149436184.jpg',
                'https://img.freepik.com/free-psd/3d-illustration-person-with-glasses_23-2149436191.jpg',
                'https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436178.jpg'
            ];
            $user = User::create([
                'avatar' => "https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg",
                'uuid' => Str::uuid(),
                'full_name' => $request->first_name . ' ' . $request->last_name,
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'first_name' => $first_name,
                'last_name' => $request->last_name,
                'avatar_url' => $list_avatars[rand(0, count($list_avatars) - 1)]
            ]);

            UserRole::create([
                'user_id' => $user->id,
                'role_id' => 1
            ]);
            $checkCode->delete();

            $token = auth('api')->login($user);
            $user->roles = ['User'];
            return Response::json(true, 'Register successfully', $user, $this->respondWithToken($token));
        } catch (Exception $e) {
            return Response::json(false, "Error : " . $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *      path="/api/auth/check-email",
     *      operationId="check-email",
     *      tags={"Users"},
     *      summary="check-email ",
     *      description="Check email",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="your email",
     *                     type="string",
     *                      example="an@gmail.com"
     *                 )
     *             )
     *         )
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function checkEmail(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if ($validate->fails()) {
            return Response::json(false, 'Missing parameter Email', $validate->errors());
        }

        $checkEmail = User::where(['email' => $request->email])->first();
        if ($checkEmail) {
            return Response::json(false, 'Email already exists');
        }
        return Response::json(true, 'Email available');
    }
    /**
     * @OA\Post(
     *      path="/api/auth/verify-email",
     *      operationId="verify-email",
     *      tags={"Users"},
     *      summary="verify-email ",
     *      description="verify email",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="your email",
     *                     type="string",
     *                      example="quynh@gmail.com"
     *                 )
     *             )
     *         )
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function verifyEmail(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Missing parameter Email', $validate->errors());
            }

            $checkToken = PasswordResetToken::where(['email' => $request->email])->first();

            $randomNumber = rand(100000, 999999);
            if ($checkToken) {
                $checkToken->token = $randomNumber;
                $checkToken->save();
            } else {
                PasswordResetToken::create([
                    'email' => $request->email,
                    'token' => $randomNumber
                ]);
            }
            // send mail 
            $data['email'] = $request->email;
            $data['title'] = $randomNumber . " is your verify code";
            $data['code'] = $randomNumber;

            Mail::send("email.verifyemail", ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            return Response::json(true, 'Send code into your email successfully');
        } catch (Exception $e) {
            return Response::json(false, "Error from server: " . $e->getMessage());
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
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return Response::json(false, 'Missing parameters', $validator->errors());
            }
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return Response::json(false, "Email does not exist");
            }

            // check blocked user 
            if ($user->blocked_until && Carbon::now()->lessThan($user->blocked_until)) {
                $remainingTime = Carbon::parse($user->blocked_until)->diffForHumans();
                // $remainingTime =$user->blocked_until;
                return Response::json(false, "Your account is blocked. Try again in $remainingTime.");
            }

            if (!$token = auth('api')->attempt($validator->validated())) {
                $user->failed_attempts += 1;
                if ($user->failed_attempts >= 5) {
                    // Block the account for 1 day if attempts reach 5
                    $user->blocked_until = Carbon::now()->addDay();
                    $user->failed_attempts = 0; // Reset failed attempts
                    $user->save();
                    return Response::json(false, "Your account is blocked for 1 day due to too many failed login attempts.");
                }

                $user->save();
                return Response::json(false, 'Incorrect password!');
            }

            // reset failed attempts on unsuccessful login
            $user->failed_attempts = 0;
            $user->blocked_until = null;
            $user->save();

            $user->roles = $user->roles();
            return Response::json(true, 'Login successfully!', $user, $this->respondWithToken($token));
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());

        }
    }
    /**
     * @OA\Get(
     *      path="/api/auth/me",
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
        try {
            $user = auth('api')->user();
            if ($user == null)
                return Response::json(false, "Unauthorized");
            $user->roles = $user->roles();

            $cart_ids = Cart::where('user_id', $user->id)->pluck('course_id');
            $cart = Course::whereIn('id', $cart_ids)->with(['user'])->get()->map(function ($item) {
                $item->rating = $item->rating();
                return $item;
            });
            $course_ids = Enrollment::where('user_id', $user->id)->pluck('course_id');
            $user->cart = $cart;
            $user->course_ids = $course_ids;
            $user->socials = $user->socials;
            return Response::json(true, 'Success', $user);
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
    }
    /**
     * @OA\Post(
     *      path="/api/auth/change_password",
     *      operationId="change_password",
     *      tags={"Users"},
     *      summary="Change new password",
     *      description="Returns status",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *               mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="password_old",
     *                     description="Enter the old password",
     *                     type="string",
     *                      example=""
     *                 ),
     *                 @OA\Property(
     *                     property="password_new",
     *                     description="Enter the new password",
     *                     type="string",
     *                      example=""
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirm",
     *                     description="Enter the password confirmation",
     *                     type="string",
     *                      example=""
     *                 )
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
            $validate = Validator::make($request->all(), [
                'password_old' => 'required',
                'password_new' => 'required',
                'password_confirm' => 'required',
            ]);
            if ($validate->fails()) {
                return Response::json(false, 'Vui lòng nhập đầy đủ thông tin!', $validate->errors());
            }
            if ($request->password_new != $request->password_confirm) {
                return Response::json(false, 'Mật khẩu xác nhận không khớp!');
            }

            $user = auth('api')->user();
            if (!Hash::check($request->password_old, $user->password)) {
                return Response::json(false, 'Mật khẩu cũ không đúng!');
            }
            $user->password = Hash::make($request->password_new);
            $user->save();
            return Response::json(true, 'Cập nhật mật khẩu mới thành công!');
        } catch (Exception $e) {
            return Response::json(false, 'Error: ' . $e->getMessage());
        }
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
     *      path="/api/user/teachers",
     *      operationId="listteachers",
     *      tags={"Users"},
     *      summary="Get list teachers information",
     *      description="Returns list teachers",
     *      @OA\Parameter(
     *         description="Page number ",
     *         in="query",
     *         name="page",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *      @OA\Parameter(
     *         description="Limit per page",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */
    public function get_teacher_list(Request $request)
    {
        try {
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 10;
            $role = Role::where('name', 'Teacher')->first();
            if (!$role) {
                return Response::json(false, "Teacher role does not exist");
            }
            $user_ids = UserRole::where('role_id', $role->id)->pluck('user_id')->all();
            $teachers = User::whereIn('id', $user_ids)->with('TeacherInfo')->paginate($limit, ['*'], 'page', $page);

            $teachers->getCollection()->transform(function ($item) {
                $item->roles = $item->roles();
                return $item;
            });

            return Response::json(true, 'Get teacher list successfully!', $teachers->items(), Response::pagination($teachers));
        } catch (Exception $e) {
            return Response::json(false, "Error from server: " . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/api/user/teacher_info/{username}",
     *      operationId="teacher_info",
     *      tags={"Users"},
     *      summary="Get  teacher information",
     *      description="Returns  teachers",
     *      @OA\Parameter(
     *         description="username of teacher",
     *         in="path",
     *         name="username",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */
    public function teacher_info($username)
    {
        try {
            if (!$username) {
                return Response::json(false, "Username is required");
            }
            $teacher = User::where('username', $username)->with('socials')->first();
            if (!$teacher) {
                return Response::json(false, "Teacher not found");
            }
            $roles = $teacher->roles();
            if (!in_array('Teacher', $roles->toArray())) {
                return Response::json(false, "User is not a teacher");
            }
            $teacher->roles = $roles;
            $teacher->teacher_info = $teacher->TeacherInfo;
            $teacher_dashboard['count_courses'] = $teacher->count_courses();
            $teacher_dashboard['count_students'] = $teacher->count_students();
            $teacher->teacher_dashboard = $teacher_dashboard;

            return Response::json(true, 'Get teacher information successfully!', $teacher);

        } catch (Exception $e) {
            return Response::json(false, "Error from server: " . $e->getMessage());
        }
    }
    /**
     * @OA\Get(
     *      path="/api/user/user_info/{username}",
     *      operationId="user_info",
     *      tags={"Users"},
     *      summary="Get  user_info information",
     *      description="Returns  user_info",
     *      @OA\Parameter(
     *         description="username of user_info",
     *         in="path",
     *         name="username",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */
    public function user_info($username)
    {
        try {
            if (!$username) {
                return Response::json(false, "Username is required");
            }
            $user = User::where('username', $username)->with('socials')->first();
            if (!$user) {
                return Response::json(false, "Teacher not found");
            }
            $roles = $user->roles();
            $user->roles = $roles;
            // $user->teacher_info = $user->TeacherInfo;
            // $teacher_dashboard['count_courses'] = $user->count_courses();
            // $teacher_dashboard['count_students'] = $user->count_students();
            // $user->teacher_dashboard = $teacher_dashboard;

            return Response::json(true, 'Get user information successfully!', $user);

        } catch (Exception $e) {
            return Response::json(false, "Error from server: " . $e->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/api/user/{username}/courses",
     *      operationId="user_courses",
     *      tags={"Users"},
     *      summary="Get  user_courses information",
     *      description="Returns  user_info",
     *      @OA\Parameter(
     *         description="username of user_info",
     *         in="path",
     *         name="username",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     * 
     *     
     * )
     */

    public function update_user_info(Request $request)
    {
        try {
            $user = auth('api')->user();
            if ($request->first_name && $request->first_name != '') {
                $user->first_name = $request->first_name;
            }
            if ($request->last_name && $request->last_name != '') {
                $user->last_name = $request->last_name;
            }
            if ($request->phone_number && $request->phone_number != '') {
                $user->phone_number = $request->phone_number;
            }
            if ($request->hasFile('avatar_url')) {
                $file = Cloudinary::uploadVideo($request->avatar_url->getRealPath())->getSecurePath();
                $user->avatar_url = $file;
            }
            $roles = $user->roles();
            $user->roles = $roles;
            $user->socials = $user->socials;

            $user->save();
            return Response::json(true, 'Update user profile successful!', $user);
        } catch (Exception $e) {
            return Response::json(false, "Error from server: " . $e->getMessage());
        }
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
                $user->roles = ['User'];
                return Response::json(true, 'Đăng nhập thành công!', $user, $this->respondWithToken($token));
            } else {
                $user = User::create([
                    'avatar_url' => $data['picture'],
                    'thumbnail_url' => $data['picture'],
                    'uuid' => Str::uuid(),
                    'full_name' => $data['name'],
                    'username' => explode('@', $data['email'])[0],
                    'email' => $data['email'],
                    'email_verified_at' => now(),
                    'first_name' => $data['family_name'],
                    'last_name' => $data['given_name'],
                ]);
                if ($user) {
                    UserRole::create([
                        'user_id' => $user->id,
                        'role_id' => 1
                    ]);
                }

                $token = auth('api')->login($user);
                $user->roles = ['User'];
                return Response::json(true, 'Đăng kí thành công!', $user, $this->respondWithToken($token));
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

            $url = "https://course.mr-quynh.com/forgot-password/" . $token;

            $data['title'] = "Xác nhận thay đổi mật khẩu mới tại Quin Course";
            $data['url'] = $url;
            $data['user'] = $user;
            $data['email'] = $user->email;

            Mail::send("email.mailchangepassword", ['data' => $data], function ($message) use ($data) {
                $message->to($data['email'])->subject($data['title']);
            });

            return Response::json(true, "Vui lòng kiểm tra email để xác nhận thay đổi mật khẩu!");


        } catch (Exception $e) {
            return Response::json(false, "Error: " . $e->getMessage());
        }



    }

    /**
     * @OA\Post(
     *      path="/api/auth/changenewpassword",
     *      operationId="changenewpassword",
     *      tags={"Users"},
     *      summary="changenewpassword",
     *      description="changenewpassword",
     *     
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="pasword",
     *                     description="Your email",
     *                     type="string",
     *                 ),
     *             ),
     *          @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     description="Token",
     *                     type="string",
     *                 ),
     *             )
     *         ),
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function changenewpassword(Request $request)
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
    /**
     * @OA\Post(
     *      path="/api/auth/update-profile",
     *      operationId="update-profile",
     *      tags={"Users"},
     *      summary="update-profile",
     *      description="update-profile",
     *     
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="first_name",
     *                     description="first name",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="last_name",
     *                     description="last name",
     *                     type="string"
     *                 ),@OA\Property(
     *                     property="phone_number",
     *                     description="phone_number",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="avatar_url",
     *                     description="Avatar ",
     *                    type="file",
     *                     format="file"
     *                 ),
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
            $user = auth('api')->user();

            if ($request->first_name) {
                $user->first_name = $request->first_name;
            }
            if ($request->last_name) {
                $user->last_name = $request->last_name;
            }
            if ($request->phone_number) {
                $user->phone_number = $request->phone_number;
            }
            if ($request->hasFile('avatar_url')) {
                $avatar_url = Cloudinary::upload($request->file('avatar_url')->getRealPath())->getSecurePath();
                $user->avatar_url = $avatar_url;
            }
            $user->save();
            $user->roles = $user->roles();
            return Response::json(true, 'Cập nhật thông tin thành công!', $user);
        } catch (Exception $e) {
            return Response::json(false, "Error: " . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *      path="/api/auth/update-social",
     *      operationId="update-social",
     *      tags={"Users"},
     *      summary="update-social",
     *      description="update-social",
     *     
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="facebook",
     *                     description="",
     *                     type="string",
     *                 ),
     *             ),
     *          @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="youtube",
     *                     description="Token",
     *                     type="string",
     *                 ),
     *             ),
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="zalo",
     *                     description="",
     *                     type="string",
     *                 ),
     *             ),
     *         ),
     *     ),
     *      @OA\Response(response="405", description="Invalid input"),
     * )
     */
    public function update_social(Request $request)
    {
        try {
            $user = auth('api')->user();

            if ($request->facebook && $request->facebook != '') {

                $check_fb = Usersocial::where(['user_id' => $user->id, 'type' => 'facebook'])->first();

                if ($check_fb) {
                    $check_fb->url = $request->facebook;
                    $check_fb->save();
                } else {
                    Usersocial::create([
                        'user_id'=>$user->id,
                        'name' => 'FaceBook',
                        'type' => 'facebook',
                        'url' => $request->facebook
                    ]);
                }
            }
            if ($request->youtube && $request->youtube != '') {
                $check_fb = Usersocial::where(['user_id' => $user->id, 'type' => 'youtube'])->first();
                if ($check_fb) {
                    $check_fb->url = $request->youtube;
                    $check_fb->save();
                } else {
                    Usersocial::create([
                        'user_id'=>$user->id,
                        'name' => 'Youtube',
                        'type' => 'youtube',
                        'url' => $request->youtube
                    ]);
                }
            }
            if ($request->zalo && $request->zalo != '') {
                $check_fb = Usersocial::where(['user_id' => $user->id, 'type' => 'zalo'])->first();
                if ($check_fb) {
                    $check_fb->url = $request->zalo;
                    $check_fb->save();
                } else {
                    Usersocial::create([
                        'user_id'=>$user->id,
                        'name' => 'Zalo',
                        'type' => 'zalo',
                        'url' => $request->zalo
                    ]);
                }
            }
            return Response::json(true, 'Cập nhật thông tin thành công!');
        } catch (Exception $e) {
            return Response::json(false, "Error: " . $e->getMessage());
        }
    }


}

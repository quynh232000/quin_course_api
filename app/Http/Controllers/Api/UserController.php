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
use App\Models\UserRole;
use Carbon\Carbon;
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
                'full_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'code' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return Response::json(false, 'Missing parameters', $validator->errors());
            }
            // check full_name has to words 
            $words = explode(' ', $request->full_name);
            if (count($words) < 2) {
                return Response::json(false, 'Full name must have at least two words');
            }
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
            $first_name = implode(' ', array_slice($words, 0, count($words) - 1));

            $list_avatars = [
                'https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436188.jpg',
                'https://img.freepik.com/free-psd/3d-illustration-bald-person-with-glasses_23-2149436184.jpg',
                'https://img.freepik.com/free-psd/3d-illustration-person-with-glasses_23-2149436191.jpg',
                'https://img.freepik.com/free-psd/3d-illustration-person-with-sunglasses_23-2149436178.jpg'
            ];
            $user = User::create([
                'avatar' => "https://img.freepik.com/free-vector/businessman-character-avatar-isolated_24877-60111.jpg",
                'uuid' => Str::uuid(),
                'full_name' => $request->full_name,
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'first_name' => $first_name,
                'last_name' => $words[count($words) - 1],
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
            return Response::json(true, 'Success', $user);
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
            $teachers = User::whereIn('id', $user_ids)->paginate($limit, ['*'], 'page', $page);

            $teachers->getCollection()->transform(function ($item) {
                $item->roles = $item->roles();
                return $item;
            });

            return Response::json(true, 'Get teacher list successfully!', $teachers->items(), Response::pagination($teachers));
        } catch (Exception $e) {
            return Response::json(false, "Error from server: " . $e->getMessage());
        }
    }
}

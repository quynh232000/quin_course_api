<?php


use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\Blogcontroller;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\Commoncontroller;
use App\Http\Controllers\Api\Coursecontroller;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoucherController;
use App\Http\Controllers\Common\VideoController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/docs/api-docs.json', function () {
    return response()->json(\Swagger\scan(app_path()));
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('check-email', [UserController::class, 'checkEmail']);
    Route::post('verify-email', [UserController::class, 'verifyEmail']);
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
    Route::post('withgoogle', [UserController::class, 'googleAuthentication']);
    Route::post('forgotpassword', [UserController::class, 'forgotpassword']);
    Route::post('changenewpassword', [UserController::class, 'changenewpassword']);
    Route::middleware([JwtMiddleware::class])->group(function () {
        Route::get('me', [UserController::class, 'me']);
    });
});
Route::prefix('common')->group(function () {
    Route::get('banks', [Commoncontroller::class, 'getBanks']);
    Route::get('levels', [Commoncontroller::class, 'get_levels']);
    Route::prefix('/video')->group(function () {
        Route::get('/getinfo/{id}', [VideoController::class, 'getVideoInfo']);
    });
});
Route::prefix('/category')->group(function () {
    Route::get('/get_child/{id}', [CategoryController::class, 'get_child']);
    Route::get('/all', [CategoryController::class, 'all']);
});
Route::get('/banners', [BannerController::class, 'all']);
Route::prefix('/blogs')->group(function () {
    Route::get('/all', [Blogcontroller::class, 'all']);
    Route::get('my_blogs', [Blogcontroller::class, 'my_blogs'])->middleware([JwtMiddleware::class]);
    Route::post('create', [Blogcontroller::class, 'create_blog'])->middleware([JwtMiddleware::class]);
    Route::post('update/{id}', [Blogcontroller::class, 'update_blog'])->middleware([JwtMiddleware::class]);
    Route::post('{blog_id}/delete_tag_blog/{tag_id}', [Blogcontroller::class, 'delete_tag_blog'])->middleware([JwtMiddleware::class]);
    Route::get('{blog_id}/same_author', [Blogcontroller::class, 'blog_same_author']);
    Route::get('tag/{tag_slug}', [Blogcontroller::class, 'blog_by_tag_slug']);
    Route::post('delete_blog/{blog_id}', [Blogcontroller::class, 'delete_blog']);

    Route::get('/{slug}', [Blogcontroller::class, 'detail']);
});
Route::prefix('/tags')->group(function () {
    Route::get('/all', [Blogcontroller::class, 'get_all_tags']);
});
Route::prefix('/course')->group(function () {
    Route::get('/filter', [Coursecontroller::class, 'filter_course']);
    Route::get('/collection', [Coursecontroller::class, 'course_collection']);
    Route::get('/detail/{slug}', [Coursecontroller::class, 'detail_course']);
    Route::get('/course_join_username', [Coursecontroller::class, 'course_join_username']);
});
Route::prefix('/user')->group(function () {
    Route::get('/teachers', [UserController::class, 'get_teacher_list']);
    Route::get('/teacher_info/{username}', [UserController::class, 'teacher_info']);
    Route::get('/user_info/{username}', [UserController::class, 'user_info']);
});
Route::prefix('voucher')->group(function () {
    Route::get('/', [VoucherController::class, 'get_list']);
    Route::get('/check/{code}', [VoucherController::class, 'check_voucher']);
});
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::prefix('cart')->group(function () {
        Route::post('addcart/{id}', [CartController::class, 'addcart']);
        Route::post('deletecart/{id}', [CartController::class, 'deletecart']);
        Route::post('asyn_cart', [CartController::class, 'asyn_cart']);
        Route::get('my_cart', [CartController::class, 'my_cart']);
    });
    Route::prefix('order')->group(function () {
        Route::post('/create', [OrderController::class, 'create_order']);
        Route::post('/confirmpayment/{order_id}/{order_code}', [OrderController::class, 'confirmpayment']);
        Route::post('checkpayment/{order_id}/{order_code}', [OrderController::class, 'checkpayment']);
        Route::post('{order_code}/check_amount', [OrderController::class, 'check_amount']);
        Route::get('my_order', [OrderController::class, 'my_order']);
        Route::get('my_order', [OrderController::class, 'my_order']);
    });
    Route::prefix('course')->group(function () {
        Route::post('/enroll/{course_id}', [Coursecontroller::class, 'enroll_course']);
        Route::get('/learning/{course_slug}', [CourseController::class, 'get_course_info']);
        Route::post('user_progress/{course_id}/{step_uuid}', [CourseController::class, 'user_progress']);
        Route::get('step/{course_id}/{step_uuid}', [CourseController::class, 'step_info']);
        Route::get('test/{uuid}', [CourseController::class, 'test']);
        Route::post('user_quiz/{id}',[Coursecontroller::class,'user_quiz']);
        Route::get('certificate/{slug}',[Coursecontroller::class,'certificate']);
    });
    Route::prefix('notes')->group(function () {
        Route::post('create', [NoteController::class, 'create_note']);
        Route::get('my_notes/{step_id}', [NoteController::class, 'get_my_notes']);
        Route::post('update/{note_id}', [NoteController::class, 'update_note']);
        Route::post('delete/{note_id}', [NoteController::class, 'delete_note']);
        // Route::get('my_notes_by_course/{course_id}', [CourseController::class, 'get_my_notes_by_course']); 
    });
    Route::prefix('comments')->group(function () {
        Route::post('create',[CommentController::class,'create_comment']);
        Route::post('delete/{id}',[CommentController::class,'delete_comment']);
        Route::post('update/{id}',[CommentController::class,'update_comment']);
    });
    Route::post('reaction/{id}',[CommentController::class,'reaction_comment']);
    Route::post('reviews/create',[ReviewController::class,'create_review']);
    Route::prefix('blog')->group(function(){
        Route::post('{id}/save',[BlogController::class,'save_blog']);
        Route::get('mysaved',[BlogController::class,'mysaved']);
        Route::post('{id}/love',[BlogController::class,'loveblog']);
        Route::get('/{id}/info', [Blogcontroller::class, 'get_blog_by_id']);
    });
    Route::prefix('auth')->group(function(){
        Route::post('change_password',[UserController::class, 'change_password']);
        Route::post('update-profile',[UserController::class, 'update_profile']);
        Route::post('update-social',[UserController::class, 'update_social']);
    });

});
Route::get('comments/list/{commentable_id}',[CommentController::class,'get_list_comments']);
Route::get('real_check_payment/{order_code}', [OrderController::class, 'real_check_payment']);

Route::get('test/{uuid}', [CourseController::class, 'test']);



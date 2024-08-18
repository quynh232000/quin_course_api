<?php


use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\Commoncontroller;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Common\VideoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




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
});
Route::prefix('common')->group(function () {
    Route::get('banks', [Commoncontroller::class, 'getBanks']);
    Route::prefix('/video')->group(function () {
        Route::get('/getinfo/{id}', [VideoController::class, 'getVideoInfo']);
    });
});
Route::prefix('/category')->group(function(){
    Route::get('/get_child/{id}', [CategoryController::class, 'get_child']);
});

// Route::middleware([JwtMiddleware::class])->group(function () {
//     Route::get('me', [AuthController::class, 'me']);
//     Route::post('logout', [AuthController::class, 'logout']);
//     Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::prefix('/auth')->group(function () {
//         Route::post('/update_profile', [AuthController::class, 'update_profile']);
//         Route::post('/change_password', [AuthController::class, 'change_password']);
//     });


// });


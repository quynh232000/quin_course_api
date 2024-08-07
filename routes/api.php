<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('withgoogle', [AuthController::class, 'googleAuthentication']);
    Route::post('forgotpassword', [AuthController::class, 'forgotpassword']);
    Route::post('changepassword', [AuthController::class, 'changepassword']);
});

Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::prefix('/auth')->group(function () {
        Route::post('/update_profile', [AuthController::class, 'update_profile']);
        Route::post('/change_password', [AuthController::class, 'change_password']);
    });


});


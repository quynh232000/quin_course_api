<?php



use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;



Route::get("/",[AdminController::class,'dashboard'])->name("dashboad");

Route::prefix("/roles")->group(function(){
    Route::get("/",[RoleController::class,'roles'])->name("list");
    Route::post("/",[RoleController::class,'createRoles'])->name("create_role");
    Route::get("/delete/{id}",[RoleController::class,'delete']);
});
Route::prefix("/category")->group(function(){
    Route::get("/",[RoleController::class,'roles']);
    Route::post("/",[RoleController::class,'createRoles']);
    Route::get("/delete/{id}",[RoleController::class,'delete']);
});

// Route::get('/login', function () {
//     return view('login');
// })->name('login');
// Route::post('/login', [AdminController::class, 'login_']);

// Route::middleware([AdminMiddleware::class])->group(function () {
//     Route::get('/', [AdminController::class, 'list'])->name('list');
    

// });


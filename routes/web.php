<?php



use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Common\VideoController;
use App\Http\Middleware\AdminRoleMiddleware;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\TeacherInfoMiddleWare;
use App\Models\LevelCourse;
use Illuminate\Support\Facades\Route;



Route::middleware([AdminRoleMiddleware::class . ":Admin"])->group(function () {
    Route::get("/", [AdminController::class, 'dashboard'])->name("admin.dashboad");
    Route::prefix("/roles")->group(function () {
        Route::get("/", [RoleController::class, 'roles'])->name("list");
        Route::post("/", [RoleController::class, 'createRoles'])->name("create_role")->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::get("/delete/{id}", [RoleController::class, 'delete'])->middleware([PermissionMiddleware::class . ':Admin delete']);
    });
    Route::prefix("/categories")->group(function () {
        Route::get("/", [CategoryController::class, 'index']);
        Route::get("/{id}", [CategoryController::class, 'get_category_info']);
        Route::post("/", [CategoryController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/update/{id}", [CategoryController::class, 'update'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/{id}", [CategoryController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::get("/delete/{id}", [CategoryController::class, 'delete'])->middleware([PermissionMiddleware::class . ':Admin delete']);
    });
    Route::prefix("/levels")->group(function () {
        Route::get("/", [LevelController::class, 'index']);
        Route::get("/{id}", [LevelController::class, 'get_level_info']);
        Route::post("/", [LevelController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/update/{id}", [LevelController::class, 'update'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/{id}", [LevelController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::get("/delete/{id}", [LevelController::class, 'delete'])->middleware([PermissionMiddleware::class . ':Admin delete']);
    });

    Route::prefix("/users")->group(function () {
        Route::get("/", [UserController::class, 'index']);
        Route::post("/{uuid}/status", [UserController::class, 'updateStatus'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/{uuid}/iscomment", [UserController::class, 'iscomment'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/{uuid}/addrole", [UserController::class, 'addRole'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::get("/{uuid}/deleterole/{id}", [UserController::class, 'deleteRole'])->middleware([PermissionMiddleware::class . ':Admin delete']);
        Route::post("/{uuid}/updateinfo", [UserController::class, 'updateInfo'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/{uuid}/changeavatar", [UserController::class, 'changeavatar'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/{uuid}/changethumbnail", [UserController::class, 'changethumbnail'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::get("/{uuid}", [UserController::class, 'userdetail']);
    });
    Route::get("/logout", [AuthController::class, 'logout']);

    Route::prefix('/teacher')->group(function () {
        Route::get('updateinfo', [TeacherController::class, 'updateinfo']);
        Route::post('updateinfo', [TeacherController::class, '_updateinfo']);
    });
    Route::middleware([TeacherInfoMiddleWare::class . ':Teacher'])->prefix('/course')->group(function () {
        Route::get('create/{step}', [TeacherController::class, 'createCourse']);
        Route::post('create/{step}', [TeacherController::class, '_createCourse']);
        Route::get('{id}/manage/goals', [CourseController::class, 'course_goals'])->name('course.manage.goals');
        Route::post('{id}/manage/goals', [CourseController::class, '_course_goals'])->name('course.manage._goals');
        Route::get('{id}/manage/goals/delete/{goal_id}', [CourseController::class, 'course_goals_delete'])->name('course.manage._goals.delete');

        Route::prefix('{id}/manage/curriculum')->group(function() {
            // Route::get('{id}/manage/curriculum', [CourseController::class, 'course_curriculum'])->name('course.manage.course_curriculum');
            Route::get('/', [CourseController::class, 'course_curriculum'])->name('course.manage.course_curriculum');
            Route::get('/{section_id}', [CourseController::class, 'course_curriculum'])->name('course.manage.course_section_edit');
            Route::post('/', [CourseController::class, '_course_curriculum'])->name('course.manage._course_curriculum');
            Route::post('/{section_id}', [CourseController::class, '_course_curriculum'])->name('course.manage._course_curriculum_edit');
            Route::get('/delete_section/{section_id}', [CourseController::class, 'delete_section'])->name('course.manage.delete_section');
            Route::get('section/{section_id}',[CourseController::class, 'course_curriculum_section'])->name('course.manage.course_curriculum_section');      
            Route::get('section/{section_id}/lecture/{step_id}',[CourseController::class, 'course_curriculum_lecture'])->name('course.manage.course_curriculum_lecture');      
            Route::get('section/{section_id}/quiz/{step_id}',[CourseController::class, 'course_curriculum_quiz'])->name('course.manage.course_curriculum_quiz');      
            Route::get('section/{section_id}/asm/{step_id}',[CourseController::class, 'course_curriculum_asm'])->name('course.manage.course_curriculum_asm');      
           
        });
        Route::get('{id}/manage/basics', [CourseController::class, 'course_basics'])->name('course.manage.course_basics');
        Route::post('{id}/manage/basics', [CourseController::class, '_course_basics'])->name('course.manage._course_basics');
        Route::get('{id}/manage/pricing', [CourseController::class, 'course_pricing'])->name('course.manage.course_pricing');
        Route::post('{id}/manage/pricing', [CourseController::class, '_course_pricing'])->name('course.manage._course_pricing');
        Route::get('{id}/manage/certificate', [CourseController::class, 'course_certificate'])->name('course.manage.course_certificate');
        Route::post('{id}/manage/certificate', [CourseController::class, '_course_certificate'])->name('course.manage._course_certificate');
        Route::get('instructor', [CourseController::class, 'instructor'])->name('course.instructor');
        Route::get('delete/{id}', [CourseController::class, 'delete'])->name('course.delete');
    });
    Route::get('/notfund', function () {
        return view('pages.notfund');
    });
});
Route::prefix("/auth")->group(function () {
    Route::get("/login", [AuthController::class, 'login']);
    Route::post("/login", [AuthController::class, '_login']);
});


// Route::post('courses',[TeacherController::class,'_courses']);
// Route::get('course/{id}',[TeacherController::class,'course']);
// Route::post('course/{id}',[TeacherController::class,'_course']);
// Route::get('course/{id}/delete',[TeacherController::class,'deleteCourse']);
// Route::post('course/{id}/delete',[TeacherController::class,'_deleteCourse']);
// Route::get('course/{id}/edit',[TeacherController::class,'editCourse']);
// Route::post('course/{id}/edit',[TeacherController::class,'_editCourse']);
// Route::get('course/{id}/addmaterial',[TeacherController::class,'addMaterial']);
// Route::post('course/{id}/addmaterial',[TeacherController::class,'_addMaterial']);

// Route::get('/login', function () {
//     return view('login');
// })->name('login');
// Route::post('/login', [AdminController::class, 'login_']);

// Route::middleware([AdminMiddleware::class])->group(function () {
//     Route::get('/', [AdminController::class, 'list'])->name('list');


// });


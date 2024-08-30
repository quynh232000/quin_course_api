<?php



use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VoucherController;
use App\Http\Controllers\Common\VideoController;
use App\Http\Middleware\AdminRoleMiddleware;
use App\Http\Middleware\MineCourseMiddleware;
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
    Route::prefix("/tags")->group(function () {
        Route::get("/", [TagController::class, 'index']);
        Route::get("/{id}", [TagController::class, 'index']);
        Route::post("/", [TagController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::post("/{id}", [TagController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit']);
        Route::get("/delete/{id}", [TagController::class, 'delete'])->middleware([PermissionMiddleware::class . ':Admin delete']);
    });
    Route::prefix("/banners")->group(function () {
        Route::get("/", [BannerController::class, 'index'])->name('admin.banner.list');
        Route::get("/create", [BannerController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.banner.create');
        Route::post("/create", [BannerController::class, '_create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.banner._create');
        Route::get("/update/{id}", [BannerController::class, 'update'])->name('admin.banner.update');
        Route::post("/update/{id}", [BannerController::class, '_create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.banner._update');
        Route::get("/delete/{id}", [BannerController::class, 'delete'])->middleware([PermissionMiddleware::class . ':Admin delete'])->name('admin.banner.delete');
    });
    Route::prefix("/blogs")->group(function () {
        Route::get("/", [BlogController::class, 'index'])->name('admin.blog.list');
        Route::get("/{id}/deletetag/{tag_id}", [BlogController::class, 'deletetag'])->name('admin.blog.deletetag');
        Route::get("/create", [BlogController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.blog.create');
        Route::post("/create", [BlogController::class, '_create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.blog._create');
        Route::get("/update/{id}", [BlogController::class, 'update'])->name('admin.blog.update');
        Route::post("/update/{id}", [BlogController::class, '_create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.blog._update');
        Route::get("/delete/{id}", [BlogController::class, 'delete'])->middleware([PermissionMiddleware::class . ':Admin delete'])->name('admin.blog.delete');
    });
    Route::prefix("/vouchers")->group(function () {
        Route::get("/", [VoucherController::class, 'index'])->name('admin.voucher.list');
        Route::get("/{id}/deletetag/{tag_id}", [VoucherController::class, 'deletetag'])->name('admin.voucher.deletetag');
        Route::get("/create", [VoucherController::class, 'create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.voucher.create');
        Route::post("/create", [VoucherController::class, '_create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.voucher._create');
        Route::get("/update/{id}", [VoucherController::class, 'update'])->name('admin.voucher.update');
        Route::post("/update/{id}", [VoucherController::class, '_create'])->middleware([PermissionMiddleware::class . ':Admin edit'])->name('admin.voucher._update');
        Route::get("/delete/{id}", [VoucherController::class, 'delete'])->middleware([PermissionMiddleware::class . ':Admin delete'])->name('admin.voucher.delete');
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

        Route::prefix('{id}/manage/curriculum')->group(function () {
            // Route::get('{id}/manage/curriculum', [CourseController::class, 'course_curriculum'])->name('course.manage.course_curriculum');
            Route::get('/', [CourseController::class, 'course_curriculum'])->name('course.manage.course_curriculum');
            Route::get('/{section_id}', [CourseController::class, 'course_curriculum'])->name('course.manage.course_section_edit');
            Route::post('/', [CourseController::class, '_course_curriculum'])->name('course.manage._course_curriculum');
            Route::post('/{section_id}', [CourseController::class, '_course_curriculum'])->name('course.manage._course_curriculum_edit');
            Route::get('/delete_section/{section_id}', [CourseController::class, 'delete_section'])->name('course.manage.delete_section');
            Route::get('section/{section_id}', [CourseController::class, 'course_curriculum_section'])->name('course.manage.course_curriculum_section');
            Route::post('section/{section_id}', [CourseController::class, '_course_curriculum_section'])->name('course.manage._course_curriculum_section');
            Route::post('section/{section_id}/{step_id}', [CourseController::class, 'edit_title_section_step'])->name('course.manage._course_curriculum_section_step');
            Route::get('section/{section_id}/delete/{step_id}', [CourseController::class, 'delete_step'])->name('course.manage.delete_step');
            Route::get('section/{section_id}/lecture/{step_id}', [CourseController::class, 'course_curriculum_lecture'])->name('course.manage.course_curriculum_lecture');
            Route::post('section/{section_id}/lecture/{step_id}', [CourseController::class, '_course_curriculum_lecture'])->name('course.manage._course_curriculum_lecture');
            Route::get('section/{section_id}/quiz/{step_id}', [CourseController::class, 'course_curriculum_quiz'])->name('course.manage.course_curriculum_quiz');
            Route::post('section/{section_id}/quiz/{step_id}', [CourseController::class, 'course_quiz_addanswer'])->name('course.manage.course_quiz_addanswer');
            Route::post('section/{section_id}/quiz/{step_id}/addquestion', [CourseController::class, 'quiz_addquestion'])->name('course.quiz_addquestion');
            Route::post('section/{section_id}/quiz/{step_id}/quiz_setduration', [CourseController::class, 'quiz_setduration'])->name('course.quiz_setduration');
            Route::get('section/{section_id}/quiz/{step_id}/deleteanswer/{answer_id}', [CourseController::class, 'quiz_deleteanswer'])->name('course.quiz_deleteanswer');


            Route::get('section/{section_id}/asm/{step_id}', [CourseController::class, 'course_curriculum_asm'])->name('course.manage.course_curriculum_asm');

        });
        Route::get('{id}/manage/basics', [CourseController::class, 'course_basics'])->name('course.manage.course_basics');
        Route::post('{id}/manage/basics', [CourseController::class, '_course_basics'])->name('course.manage._course_basics');
        Route::get('{id}/manage/pricing', [CourseController::class, 'course_pricing'])->name('course.manage.course_pricing');
        Route::post('{id}/manage/pricing', [CourseController::class, '_course_pricing'])->name('course.manage._course_pricing');
        Route::get('{id}/manage/certificate', [CourseController::class, 'course_certificate'])->name('course.manage.course_certificate');
        Route::post('{id}/manage/certificate', [CourseController::class, '_course_certificate'])->name('course.manage._course_certificate');
        Route::get('instructor', [CourseController::class, 'instructor'])->name('course.instructor');
        Route::get('delete/{id}', [TeacherController::class, 'deletecourse'])->name('course.delete');

        Route::prefix('preview/{id}')->middleware([MineCourseMiddleware::class . ':course_id'])->group(function () {
            Route::get('/', [CourseController::class, 'preview'])->name('preview');
            Route::get('/{type}/{uuid}', [CourseController::class, 'preview_home'])->name('preview_home');
            Route::post('/published_course', [CourseController::class, 'published_course'])->name('published_course');
        });
    });
    Route::middleware([PermissionMiddleware::class . ':Super Admin'])->prefix('settings')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('admin.settings');
        Route::get("/{id}", [AdminController::class, 'settings']);
        Route::post("/", [AdminController::class, 'settings_create']);
        Route::post("/{id}", [AdminController::class, 'settings_create']);
        Route::get("/delete/{id}", [AdminController::class, 'settings_delete']);
        ;
    });
    Route::prefix('orders')->group(function () { 
        Route::get('/',[OrderController::class,'manage_orders'])->name('admin.orders');
        Route::get('/{order_code}',[OrderController::class,'order_detail'])->name('admin.order.order_detail');
        Route::get('/cancel/{order_id}',[OrderController::class,'cancel_order'])->name('admin.order.cancel');
        Route::get('/confirm/{order_id}',[OrderController::class,'confirm_order'])->name('admin.order.confirm');
    });
    Route::get('/notfund', function () {
        return view('pages.notfund');
    })->name('admin.notfund');
});
Route::prefix("/auth")->group(function () {
    Route::get("/login", [AuthController::class, 'login']);
    Route::post("/login", [AuthController::class, '_login']);

    Route::get('{provider}/redirect', [AuthController::class, 'redirect']);

    Route::get('{provider}/callback', [AuthController::class, 'callback']);
});

Route::get('/order/confirm/{order_id}', [OrderController::class, 'admin_confirm_order']);





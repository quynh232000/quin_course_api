// <?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
//     {
//         Schema::create('courses', function (Blueprint $table) {
//             $table->id();
//             $table->string('title');
//             $table->string('sub_title');
//             $table->string('slug');
//             $table->text('description');
            


//             $table->unsignedBigInteger('instructor_id');
//             $table->unsignedBigInteger('category_id');
//             $table->unsignedBigInteger('duration_minutes');
//             $table->unsignedBigInteger('price');
//             $table->unsignedBigInteger('currency_id');
//             $table->unsignedBigInteger('language_id');
//             $table->unsignedBigInteger('difficulty_id');
//             $table->unsignedBigInteger('status_id');
//             $table->unsignedBigInteger('view_count');
//             $table->unsignedBigInteger('enrollment_count');

//             $table->foreign('instructor_id')->references('id')->on('users')->onDelete('cascade');
//             $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
//             $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade');
//             $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
//             $table->foreign('difficulty_id')->references('id')->on('difficulties')->onDelete('cascade');
//             $table->foreign('status_id')->references('id')->on('course_statuses')->onDelete('cascade');
//             $table->softDeletes();
//             $table->index(['instructor_id', 'category_id']);
//             $table->index('status_id');
//             $table->index('language_id');
//             $table->index('difficulty_id');
//             $table->index('currency_id');
//             $table->index('view_count');
//             $table->index('enrollment_count');


//             $table->timestamps();
//         });
//     }

//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('courses');
//     }
// };

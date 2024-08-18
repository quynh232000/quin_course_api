<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sub_title')->nullable();
            $table->string('slug');
            $table->string('image_url')->nullable();
            $table->string('duration')->nullable();
            $table->string('certificate_name')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->text('completed_content')->nullable();
            $table->string('price')->nullable();
            $table->double('percent_sale')->default(0);
            $table->string('level_id')->default(0);
            $table->integer('priority')->default(0);
            $table->string('category_id');
            $table->string('type')->default('course');
            $table->timestamp('published_at')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('enrollment_count')->default(0);

            $table->string('video_type')->default('youtube');
            $table->string('video_url')->nullable();
            $table->string('video')->nullable();


            $table->integer('status')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

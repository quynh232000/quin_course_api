<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_lectures', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->bigInteger('step_id');
            $table->text('description');
            $table->string('video_url')->nullable();
            $table->string('video_type')->nullable();
            $table->string('video')->nullable();
            // $table->integer('duration')->nullable()->default(60);
            $table->integer('priority')->default(0);
            $table->boolean('is_show')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lectures');
    }
};

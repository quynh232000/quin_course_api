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
        Schema::create('course_steps', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->bigInteger('section_id');
            $table->text('title');
            $table->string('type');
            $table->integer('priority')->default(0);
            $table->boolean('is_preview')->default(false);
            $table->integer('duration')->nullable()->default(60);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_steps');
    }
};

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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->string('type');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('commentable_id');
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_answered')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

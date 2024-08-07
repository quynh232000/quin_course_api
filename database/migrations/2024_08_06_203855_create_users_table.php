<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('avatar_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->string('birthday')->nullable(); // Only one birthday column
            $table->string('address')->nullable();
            $table->string('password');
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_pro')->default(0);
            $table->boolean('is_comment_blocked')->default(0);
            $table->dateTime('comment_blocked_at')->nullable(); // Corrected typo from "conmment_blocked_at"
            $table->dateTime('is_learn_tour_completed')->nullable();
            $table->dateTime('is_onboarding_completed')->nullable();
            $table->string('remember_token')->nullable(); // Included only once
            $table->text('bio')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};

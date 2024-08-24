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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('description');
            $table->string('alt');
            $table->string('from');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('placement');
            $table->string('link_to');
            $table->string('banner_url');
            $table->boolean('is_blank')->default(false);
            $table->string('type');
            $table->integer('priority')->default(0);
            $table->boolean('is_show')->default(true);
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};

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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('bank_id');
            $table->string('bank_brand_name');
            $table->string('order_code');
            $table->string('account_number');
            $table->timestamp('transaction_date');
            $table->float('amount_out')->default(0.0);
            $table->float('amount_in')->default(0.0);
            $table->string('accumulated')->default('0.0');
            $table->text('transaction_content');
            $table->string('reference_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};

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
        Schema::create('cashfree_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained();
            $table->float('order_amount', 10, 2)->nullable();
            $table->string('payment_status')->nullable();
            $table->float('payment_amount', 10, 2)->nullable();
            $table->dateTime('payment_time')->nullable();
            $table->string('payment_mode')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('payment_phone')->nullable();
            $table->json('data')->nullable();
            $table->longText('enc_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashfree_payments');
    }
};

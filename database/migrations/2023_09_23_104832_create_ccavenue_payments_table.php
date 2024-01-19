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
        Schema::create('ccavenue_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained();
            $table->float('amount', 10, 2)->nullable();
            $table->dateTime('trans_date')->nullable();
            $table->string('status')->nullable();
            $table->string('tracking_id')->nullable();
            $table->float('trans_fee', 10, 2)->nullable();
            $table->float('service_tax', 10, 2)->nullable();
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
        Schema::dropIfExists('ccavenue_payments');
    }
};

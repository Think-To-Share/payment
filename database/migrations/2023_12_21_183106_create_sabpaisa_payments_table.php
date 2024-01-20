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
        Schema::create('sabpaisa_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained();
            $table->string('sabpaisaTxnId')->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->dateTime('trans_date')->nullable();
            $table->string('status')->nullable();
            $table->longText('enc_data')->nullable();
            $table->json('data')->nullable();
            $table->json('enquiry_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sabpaisa_payments');
    }
};

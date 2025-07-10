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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_intent_id')->unique();
            $table->string('status'); // 'succeeded', 'failed', 'processing', etc.
            $table->bigInteger('amount'); // in paise (e.g., ₹50 = 5000)
            $table->string('currency')->default('inr');
            $table->string('email')->nullable();
            $table->json('meta')->nullable(); // Stripe payment object
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

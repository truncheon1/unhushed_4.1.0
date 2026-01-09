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
        Schema::create('user_donations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0); // 0 = anonymous
            $table->string('payment_id', 191)->nullable(); // Stripe PaymentIntent ID
            $table->string('subscription_id', 191)->nullable(); // Stripe Subscription ID for recurring
            $table->decimal('amount', 10, 2); // Donation amount
            $table->tinyInteger('recurring')->default(0); // 1 = recurring, 0 = one-time
            $table->string('status', 50)->default('pending'); // pending, completed, failed, refunded
            $table->string('payment_method_id', 191)->nullable(); // Stripe PaymentMethod ID
            $table->string('payment_type', 50)->default('card'); // card, etc
            $table->text('message')->nullable(); // Optional donor message
            $table->tinyInteger('receipt_sent')->default(0); // 0 = not sent, 1 = sent
            $table->timestamp('receipt_sent_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->softDeletes(); // Soft delete support
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('status');
            $table->index('subscription_id');
            $table->index('recurring');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_donations');
    }
};

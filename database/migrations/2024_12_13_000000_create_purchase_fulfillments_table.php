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
        Schema::create('purchase_fulfillments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_id');
            $table->integer('status')->default(0); // 0=ordered, 1=shipped, 2=digital, 3=backorder, 4=canceled
            $table->dateTime('shipped')->nullable();
            $table->string('tracking')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('cart_id')->references('id')->on('purchase_carts')->onDelete('cascade');
            $table->index('cart_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_fulfillments');
    }
};

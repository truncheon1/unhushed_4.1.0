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
        Schema::create('product_vars', function (Blueprint $table) {
            $table->bigIncrements('var_id');
            $table->integer('product_id')->nullable()->index();
            $table->string('sku')->default('')->nullable();
            $table->string('deliver_slug')->default('')->nullable();
            $table->text('description2')->nullable();
            $table->float('price')->default(0.00);
            $table->integer('taxable')->default(1);
            $table->integer('ship_type')->default(1);
            $table->float('weight')->default(0.00);
            $table->integer('qty')->default(0);
            $table->integer('avail')->default(0);
            $table->string('paypal_id')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_vars');
    }
};

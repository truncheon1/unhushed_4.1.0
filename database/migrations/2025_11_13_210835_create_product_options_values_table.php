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
        if (!Schema::hasTable('product_options_values')) {
            Schema::create('product_options_values', function (Blueprint $table) {
            $table->bigIncrements('value_id');
            $table->integer('option_id')->nullable()->index();
            $table->string('name');
            $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_options_values');
    }
};

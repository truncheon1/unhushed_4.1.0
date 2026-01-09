<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('curriculum_prices')) {
            Schema::create('curriculum_prices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->integer('min_users')->default(0);
                $table->integer('max_users')->default(1);
                $table->float('standard_price')->default(0);
                $table->float('discount_price')->default(0);
                $table->float('recurring_price')->default(0);
                $table->string('package_caption')->default('');
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curriculum_prices');
    }
}

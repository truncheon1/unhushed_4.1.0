<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPaypalObjectsStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pay_pal_product_assocs', function (Blueprint $table) {
            $table->string('plan_id')->default('')->nullable()->change();
        });
        
        Schema::table('pay_pal_products', function (Blueprint $table) {
            $table->string('plan_id')->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

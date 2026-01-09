<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActiveSubscriptionsTable extends Migration
{
    // /**
    //  * Run the migrations.
    //  *
    //  * @return void
    //  */
    // public function up()
    // {
    //     Schema::create('active_subscriptions', function (Blueprint $table) {
    //         $table->bigIncrements('id')->unique();
    //         $table->unsignedBigInteger('org_id');
    //         $table->unsignedBigInteger('package_id');
    //         $table->integer('type');
    //         $table->integer('total');
    //         $table->integer('used');
    //         $table->integer('status');
    //         $table->timestamps();
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    // public function down()
    // {
    //     Schema::dropIfExists('active_subscriptions');
    // }
}

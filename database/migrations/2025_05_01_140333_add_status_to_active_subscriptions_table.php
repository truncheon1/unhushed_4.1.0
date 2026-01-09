<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToActiveSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // public function up()
    // {
    //     Schema::table('active_subscriptions', function (Blueprint $table) {
    //         $table->integer('status')->after('used')->default(0);
    //         $table->renameColumn('org_id', 'org_id');
    //         $table->renameColumn('type', 'type');
    //     });
    // }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    // public function down()
    // {
        // Schema::table('active_subscriptions', function (Blueprint $table) {
        //     $table->integer('status')->after('used')->default(0);
        //     $table->renameColumn('org_id', 'org_id');
        //     $table->renameColumn('type', 'type');
        // });
    //}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewShippedToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            //$table->date('shipped')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            //$table->date('shipped');
        });
    }
}

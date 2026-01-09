<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameShippedToStatusCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            //$table->renameColumn('shipped', 'status');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            //$table->renameColumn('shipped', 'status');
        });
    }
}



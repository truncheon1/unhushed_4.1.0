<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('rStat')){
            Schema::create('rStat', function (Blueprint $table) {
                $table->id();
                $table->string("title");
                $table->string("author");
                $table->string("journal");
                $table->string("year");
                $table->string("month");
                $table->string("keywords");
                $table->string("url");
                $table->string("abstract");
            });
        };
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rStat_if_not_exists');
    }
}

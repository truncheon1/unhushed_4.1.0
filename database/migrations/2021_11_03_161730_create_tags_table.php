<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
    *public function up()
    *{
    *    Schema::create('add_tags', function (Blueprint $table) {
    *        $table->id();
    *        $table->bigInteger('parent_id')->default(0);
    *        $table->integer('root_id');
    *        $table->string('name');
    *        $table->timestamps();
    *    });
    *}

    *public function down()
    *{
    *    Schema::dropIfExists('add_tags');
    *}
    */
}

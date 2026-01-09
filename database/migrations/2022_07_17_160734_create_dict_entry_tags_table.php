<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDictEntryTagsTable extends Migration
{
    public function up()
    {
        Schema::create('dict_entry_tags', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('entry_id');
            $table->bigInteger('tag_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dict_entry_tags');
    }
}

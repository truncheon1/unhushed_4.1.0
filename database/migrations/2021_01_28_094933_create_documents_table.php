<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurriculumDocumentsTable extends Migration
{

    public function up()
    {
        Schema::create('curriculum_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('sort');
            $table->string('document');
            $table->string('keywords');
            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('curriculum_units');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}

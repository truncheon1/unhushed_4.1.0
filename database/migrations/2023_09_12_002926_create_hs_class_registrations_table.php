<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHsClassRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('hs_class_registrations', function (Blueprint $table) {
            $table->id();
            //student
            $table->unsignedBigInteger('kid_user_id')->nullable();
            $table->string('kid_name');
            $table->string('kid_email')->unique()->nullable();
            $table->string('kid_phone')->nullable();
            $table->integer('age');
            $table->integer('grade');
            $table->string('gender');
            $table->string('pronouns');
            //parent1
            $table->unsignedBigInteger('parent1_user_id');
            $table->string('parent1_name');
            $table->string('parent1_email')->unique();
            $table->string('parent1_phone');
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip', 5);
            //parent2
            $table->string('add');
            $table->unsignedBigInteger('parent2_user_id')->nullable();
            $table->string('parent2_name')->nullable();
            $table->string('parent2_email')->unique()->nullable();
            $table->string('parent2_phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hs_class_registrations');
    }
}

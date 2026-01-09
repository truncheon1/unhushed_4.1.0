<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHcwpAttendeesTable extends Migration
{
    public function up()
    {
        Schema::create('hcwp_attendees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('ceu')->nullable();
            //from api
            $table->string('api_address1')->nullable();
            $table->string('api_address2')->nullable();
            $table->string('api_city')->nullable();
            $table->string('api_state')->nullable();
            $table->string('api_zip5', 5);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hcwp_attendees');
    }
}


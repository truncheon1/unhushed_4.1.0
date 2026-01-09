<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('zip', 5);
            //from api
            $table->string('api_address1')->nullable();
            $table->string('api_address2')->nullable();
            $table->string('api_city')->nullable();
            $table->string('api_state')->nullable();
            $table->string('api_zip5', 5);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_addresses');
    }
}

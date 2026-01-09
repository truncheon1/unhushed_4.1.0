<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biologies', function (Blueprint $table) {
            $table->id();
            $table->string('term');
            $table->string('chromosomes');
            $table->string('hormones');
            $table->string('gonads');
            $table->text('external');
            $table->text('internal');
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
        Schema::dropIfExists('biologies');
    }
}

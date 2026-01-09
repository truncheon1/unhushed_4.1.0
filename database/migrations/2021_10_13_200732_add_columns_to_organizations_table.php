<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            //add post image
            $table->string('parents_reached')->after('email_match')->nullable();
            $table->string('orientation_hrs')->after('parents_reached')->nullable();
            $table->string('students')->after('orientation_hrs')->nullable();
            $table->string('instruction_hrs')->after('students')->nullable();
            $table->string('subscription')->after('instruction_hrs')->nullable();
            $table->string('support')->after('subscription')->nullable();
            $table->string('facilitation')->after('support')->nullable();
            $table->string('trained')->after('facilitation')->nullable();
            $table->string('keynote')->after('trained')->nullable();
            $table->string('manual')->after('keynote')->nullable();
            $table->string('illustrations')->after('manual')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organizations', function (Blueprint $table) {
            //
        });
    }
}

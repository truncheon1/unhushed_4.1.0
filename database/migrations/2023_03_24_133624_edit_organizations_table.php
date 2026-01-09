<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('url')->after('email_match')->nullable();
            $table->string('address')->after('city')->nullable();
            $table->string('zip')->after('state')->nullable();
            $table->string('pedagogy')->after('country')->nullable();
            $table->string('size')->after('pedagogy')->nullable();
            $table->dropColumn('orientation_hrs');
            $table->dropColumn('instruction_hrs');
            $table->dropColumn('support');
            $table->dropColumn('facilitation');
            $table->dropColumn('trained');
            $table->dropColumn('keynote');
            $table->dropColumn('manual');
            $table->dropColumn('illustrations');
            $table->dropColumn('parents_reached');
            $table->dropColumn('students');
            $table->dropColumn('subscription');
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
            $table->string('url')->after('email_match')->nullable();
            $table->string('address')->after('city')->nullable();
            $table->string('zip')->after('state')->nullable();
            $table->string('pedagogy')->after('country')->nullable();
            $table->string('size')->after('pedagogy')->nullable();
            $table->dropColumn('orientation_hrs');
            $table->dropColumn('instruction_hrs');
            $table->dropColumn('support');
            $table->dropColumn('facilitation');
            $table->dropColumn('trained');
            $table->dropColumn('keynote');
            $table->dropColumn('manual');
            $table->dropColumn('illustrations');
            $table->dropColumn('parents_reached');
            $table->dropColumn('students');
            $table->dropColumn('subscription');
        });
    }
}

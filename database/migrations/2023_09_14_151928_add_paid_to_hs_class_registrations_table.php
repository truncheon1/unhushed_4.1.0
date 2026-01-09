<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidToHsClassRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hs_class_registrations', function (Blueprint $table) {
            $table->string('year')->after('id')->nullable();
            $table->dropUnique(['parent1_email']);
            $table->dropUnique(['parent2_email']);
            $table->string('paid')->nullable()->after('pronouns');
            $table->string('legal')->nullable()->after('paid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hs_class_registrations', function (Blueprint $table) {
            $table->string('year')->after('id')->nullable();
            $table->dropUnique(['parent1_email']);
            $table->dropUnique(['parent2_email']);
            $table->string('paid')->nullable()->after('pronouns');
            $table->string('legal')->nullable()->after('paid');
        });
    }
}

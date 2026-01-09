<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function($table)
        {
            $table->string('grades')->after('pedagogy')->nullable();
            //Give moving columns a temporary name:
            $table->renameColumn('city', 'city_old');
            $table->renameColumn('type', 'type_old');
        });

        //Add a new column with the regular name:
        Schema::table('organizations', function(Blueprint $table)
        {
            $table->string('city')->after('address');
            $table->string('type')->after('size');
        });

        //Copy the data across to the new column:
        DB::table('organizations')->update([
            'city' => DB::raw('city_old')   
        ]);
        DB::table('organizations')->update([
            'type' => DB::raw('type_old')   
        ]);

        //Remove the old column:
        Schema::table('organizations', function(Blueprint $table)
        {
            $table->dropColumn('city_old');
            $table->dropColumn('type_old');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function($table)
        {
            $table->string('grades')->after('pedagogy')->nullable();
            //Give moving columns a temporary name:
            $table->renameColumn('city', 'city_old');
            $table->renameColumn('type', 'type_old');
        });

        //Add a new column with the regular name:
        Schema::table('organizations', function(Blueprint $table)
        {
            $table->string('city')->after('address');
            $table->string('type')->after('size');
        });

        //Copy the data across to the new column:
        DB::table('organizations')->update([
            'city' => DB::raw('city_old')   
        ]);
        DB::table('organizations')->update([
            'type' => DB::raw('type_old')   
        ]);

        //Remove the old column:
        Schema::table('organizations', function(Blueprint $table)
        {
            $table->dropColumn('city_old');
            $table->dropColumn('type_old');
        });
    }
};

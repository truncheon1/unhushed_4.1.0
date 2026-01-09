<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add sort column to product_vars table
        if (!Schema::hasColumn('product_vars', 'sort')) {
            Schema::table('product_vars', function (Blueprint $table) {
                $table->integer('sort')->default(0)->after('avail');
            });
            
            // Set initial sort values for existing variants (ordered by var_id)
            DB::statement('SET @sort = 0');
            DB::statement('UPDATE product_vars SET sort = (@sort := @sort + 1) ORDER BY product_id, var_id');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_vars', function (Blueprint $table) {
            if (Schema::hasColumn('product_vars', 'sort')) {
                $table->dropColumn('sort');
            }
        });
    }
};

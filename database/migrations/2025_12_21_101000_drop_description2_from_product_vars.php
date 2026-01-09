<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop description2 column from product_vars table
        if (Schema::hasColumn('product_vars', 'description2')) {
            Schema::table('product_vars', function (Blueprint $table) {
                $table->dropColumn('description2');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore description2 column to product_vars table
        Schema::table('product_vars', function (Blueprint $table) {
            $table->text('description2')->nullable();
        });
    }
};

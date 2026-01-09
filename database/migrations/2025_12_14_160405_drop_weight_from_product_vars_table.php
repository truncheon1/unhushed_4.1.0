<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drops the legacy 'weight' column from product_vars table.
     * The weight data is now stored in separate weight_lbs and weight_oz columns
     * for accurate USPS shipping calculations.
     */
    public function up(): void
    {
        Schema::table('product_vars', function (Blueprint $table) {
            if (Schema::hasColumn('product_vars', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_vars', function (Blueprint $table) {
            // Restore weight column as float if migration is rolled back
            $table->float('weight')->default(0.00)->after('ship_type');
        });
    }
};

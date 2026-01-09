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
     * Drops the erroneous 'variant_id' column from product_options_vars_junction.
     * This table should only use 'var_id' to reference product_vars.
     */
    public function up(): void
    {
        // First, check if variant_id column exists
        if(Schema::hasColumn('product_options_vars_junction', 'variant_id')){
            // Try to drop the unique constraint if it exists
            try {
                DB::statement('ALTER TABLE product_options_vars_junction DROP INDEX product_options_vars_junction_variant_id_value_id_unique');
            } catch (\Exception $e) {
                // Index may not exist, ignore error
            }
            
            // Now drop the column
            Schema::table('product_options_vars_junction', function (Blueprint $table) {
                $table->dropColumn('variant_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_options_vars_junction', function (Blueprint $table) {
            // Don't restore variant_id - it was a mistake
        });
    }
};

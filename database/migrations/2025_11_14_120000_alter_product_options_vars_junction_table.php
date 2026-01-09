<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * NOTE: This migration previously added 'variant_id' column which was an error.
     * It has been removed. The junction table should only use 'var_id'.
     * Migration 2025_11_17_190000 recreated the table with correct schema.
     * Migration 2025_12_14_162429 drops any remaining variant_id column.
     */
    public function up(): void
    {
        // Ensure value_id column exists and is indexed
        if(Schema::hasTable('product_options_vars_junction')){
            Schema::table('product_options_vars_junction', function (Blueprint $table) {
                // Ensure value_id is indexed (migration already did but re-assert safety)
                if(!Schema::hasColumn('product_options_vars_junction', 'value_id')){
                    $table->unsignedBigInteger('value_id')->nullable()->index();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nothing to rollback - value_id should remain
    }
};
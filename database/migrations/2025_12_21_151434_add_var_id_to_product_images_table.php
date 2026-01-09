<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            // Add JSON column to store array of variant IDs this activity is assigned to
            // NULL means assigned to all variants (backward compatible)
            // Empty array [] means assigned to no variants
            // Array of IDs like [1,3,5] means assigned to those specific variants
            $table->json('variant_ids')->nullable()->after('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('variant_ids');
        });
    }
};

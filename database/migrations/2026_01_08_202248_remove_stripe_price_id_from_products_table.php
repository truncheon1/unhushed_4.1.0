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
        Schema::table('products', function (Blueprint $table) {
            // Remove stripe_price_id since all products MUST have entries in either:
            // - product_vars (for physical/digital products) OR
            // - curriculum_prices (for subscription products)
            // This ensures a cleaner, more organized data structure where price IDs
            // are stored at the appropriate level (variant or pricing tier)
            $table->dropColumn('stripe_price_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Restore column for rollback
            $table->string('stripe_price_id')->nullable()->after('stripe_product_id')->comment('For single-variant products');
        });
    }
};

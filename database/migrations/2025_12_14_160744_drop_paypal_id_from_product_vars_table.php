<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drops the legacy 'paypal_id' column from product_vars table.
     * PayPal IDs are no longer used in the current payment processing system.
     */
    public function up(): void
    {
        Schema::table('product_vars', function (Blueprint $table) {
            if (Schema::hasColumn('product_vars', 'paypal_id')) {
                $table->dropColumn('paypal_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_vars', function (Blueprint $table) {
            // Restore paypal_id column if migration is rolled back
            $table->string('paypal_id')->default('')->after('avail');
        });
    }
};

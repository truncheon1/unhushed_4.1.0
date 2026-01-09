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
        Schema::table('user_donations', function (Blueprint $table) {
            // Rename payment_type to payment_method
            $table->renameColumn('payment_type', 'payment_method');
            
            // Add missing columns
            $table->text('notes')->nullable()->after('message');
            $table->string('reference_number', 191)->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_donations', function (Blueprint $table) {
            $table->renameColumn('payment_method', 'payment_type');
            $table->dropColumn(['notes', 'reference_number']);
        });
    }
};

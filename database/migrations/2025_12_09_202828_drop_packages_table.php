<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Drop the packages table as it's being replaced by the products table.
     * All curriculum data is now stored in products where category = 1.
     * The packages_assignments table will continue to use product_id to reference products.
     */
    public function up(): void
    {
        // Drop foreign key constraint from curriculum_prices first
        Schema::table('curriculum_prices', function (Blueprint $table) {
            $table->dropForeign('packages_options_package_id_foreign');
        });
        
        // Now we can drop the packages table
        Schema::dropIfExists('packages');
    }

    /**
     * Reverse the migrations.
     * 
     * Recreate the packages table structure for rollback purposes.
     */
    public function down(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('slug')->nullable();
            $table->integer('sort')->default(0);
            $table->unsignedBigInteger('product_id')->nullable()->comment('Reference to products table');
            $table->string('ac_tags')->nullable();
            $table->timestamps();
        });
        
        // Recreate the foreign key constraint on curriculum_prices
        Schema::table('curriculum_prices', function (Blueprint $table) {
            $table->foreign('package_id', 'packages_options_package_id_foreign')
                  ->references('id')
                  ->on('packages')
                  ->onDelete('cascade');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_descriptions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            // Add JSON column to store array of variant IDs this activity is assigned to
            // NULL means assigned to all variants (backward compatible)
            // Empty array [] means assigned to no variants
            // Array of IDs like [1,3,5] means assigned to those specific variants
            $table->json('variant_ids')->nullable();
            $table->integer('sort');
            $table->text('description');
            $table->timestamps();
        });
    }

    // /**
    //  * Reverse the migrations.
    //  *
    //  * @return void
    //  */
    public function down()
    {
        Schema::dropIfExists('product_descriptions');
    }
}

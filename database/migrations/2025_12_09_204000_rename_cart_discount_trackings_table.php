<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('cart_discount_trackings') && !Schema::hasTable('cart_discounts')) {
            Schema::rename('cart_discount_trackings', 'cart_discounts');
        }
        //Give the moving column a temporary name:
        Schema::table('cart_discounts', function($table)
        {
            $table->renameColumn('cart_id', 'cart_id_old');
            $table->renameColumn('discount_id', 'discount_id_old');
            $table->renameColumn('type', 'type_old');
        });

        //Add a new column with the regular name:
        Schema::table('cart_discounts', function(Blueprint $table)
        {
            $table->string('cart_id')->after('id');
            $table->string('discount_id')->after('cart_id');
            $table->string('type')->after('discount_id');
        });

        //Copy the data across to the new column:
        DB::table('cart_discounts')->update([
            'cart_id' => DB::raw('cart_id_old'),
            'discount_id' => DB::raw('discount_id_old'),
            'type' => DB::raw('type_old')
        ]);

        //Remove the old column:
        Schema::table('cart_discounts', function(Blueprint $table)
        {
            $table->dropColumn('cart_id_old');
            $table->dropColumn('discount_id_old');
            $table->dropColumn('type_old');
        });
        }

    public function down(): void
    {
        if (Schema::hasTable('cart_discounts') && !Schema::hasTable('cart_discount_trackings')) {
            Schema::rename('cart_discounts', 'cart_discount_trackings');
        }
    }
};

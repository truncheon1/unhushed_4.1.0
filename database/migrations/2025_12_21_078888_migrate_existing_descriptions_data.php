<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class MigrateExistingDescriptionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Migrate products.description to product_descriptions
        // This will be the "main" description assigned to all variants (variant_ids = NULL)
        $products = DB::table('products')
            ->whereNotNull('description')
            ->where('description', '!=', '')
            ->get();

        foreach ($products as $product) {
            DB::table('product_descriptions')->insert([
                'product_id' => $product->id,
                'variant_ids' => null, // NULL = assigned to all variants
                'sort' => 0, // Main description is first
                'description' => $product->description,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Migrate product_vars.description2 to product_descriptions
        // Each variant gets its own description record with variant_ids = [var_id]
        $variants = DB::table('product_vars')
            ->whereNotNull('description2')
            ->where('description2', '!=', '')
            ->get();

        foreach ($variants as $variant) {
            // Check if this product already has a main description (sort = 0)
            $hasMainDescription = DB::table('product_descriptions')
                ->where('product_id', $variant->product_id)
                ->where('sort', 0)
                ->exists();
            
            // Get the next available sort order for this product
            $maxSort = DB::table('product_descriptions')
                ->where('product_id', $variant->product_id)
                ->max('sort');
            
            $nextSort = ($maxSort !== null) ? $maxSort + 1 : 0;

            DB::table('product_descriptions')->insert([
                'product_id' => $variant->product_id,
                'variant_ids' => json_encode([$variant->var_id]), // Array with single var_id
                'sort' => $nextSort,
                'description' => $variant->description2,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "Migration complete:\n";
        echo "- Migrated " . $products->count() . " product descriptions\n";
        echo "- Migrated " . $variants->count() . " variant descriptions\n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Clear all migrated data
        DB::table('product_descriptions')->truncate();
        
        echo "Rolled back: All product_descriptions records deleted\n";
        echo "Note: Original data in products.description and product_vars.description2 remains intact\n";
    }
}

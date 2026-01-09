<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Map type values to category values
        $typeToCategory = [
            5 => 1,  // curriculum
            1 => 2,  // activity
            3 => 3,  // book
            6 => 4,  // game
            7 => 5,  // swag
            8 => 6,  // toolkit
            9 => 7,  // training
        ];

        // Get all records from products_collections
        $collections = DB::table('products_collections')->get();

        foreach ($collections as $collection) {
            // Extract sort number (everything after '-')
            $sortParts = explode('-', $collection->sort);
            $sortNumber = count($sortParts) > 1 ? $sortParts[1] : $collection->sort;

            // Map type to category
            $category = $typeToCategory[$collection->type] ?? 0;

            // Insert into products table
            DB::table('products')->insert([
                'id' => $collection->id,
                'name' => $collection->name,
                'slug' => $collection->slug ?? '',
                'description'   => $collection->description ?? '',
                'ac_tags' => $collection->ac_tags ?? '',
                'category' => $category,
                'sort' => $sortNumber,
                'created_at' => $collection->created_at ?? now(),
                'updated_at' => $collection->updated_at ?? now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear all records from products table that came from products_collections
        $collectionIds = DB::table('products_collections')->pluck('id');
        DB::table('products')->whereIn('id', $collectionIds)->delete();
    }
};

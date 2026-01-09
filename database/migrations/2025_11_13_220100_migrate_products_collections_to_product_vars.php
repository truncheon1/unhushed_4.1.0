<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Fetch in chunks to avoid memory spikes on large tables
        DB::table('products_collections')
            ->orderBy('id')
            ->chunk(500, function($rows){
                $insertBatch = [];
                foreach($rows as $row){
                    // Skip if product_vars already exists for this product_id
                    $exists = DB::table('product_vars')->where('product_id', $row->id)->exists();
                    if($exists) continue;

                    // Map taxable: 2 (nontaxable) -> 0, 1 (taxable) -> 1, default 1
                    $taxable = match($row->taxable ?? 1){
                        2 => 0,
                        1 => 1,
                        default => 1,
                    };

                    // Map shipping_type: 0 digital, 1 standard mail, 2 media mail
                    $shipType = match($row->shipping_type ?? 1){
                        0 => 0,
                        1 => 1,
                        2 => 2,
                        default => 1,
                    };

                    // Extract fields safely (fallbacks for missing columns)
                    $insertBatch[] = [
                        'product_id'    => $row->id,
                        'sku'           => '',
                        'deliver_slug'  => $row->digital_slug ?? '',
                        'description2'  => '',
                        'price'         => (float) ($row->price ?? 0.00),
                        'taxable'       => $taxable,
                        'ship_type'     => $shipType,
                        'weight'        => (float) ($row->weight ?? 0.00),
                        'qty'           => (int) ($row->qty ?? 0),
                        'avail'         => (int) ($row->available ?? 0),
                        'paypal_id'     => '',
                        'created_at'    => $row->created_at ?? now(),
                        'updated_at'    => now(),
                    ];
                }
                if(!empty($insertBatch)){
                    DB::table('product_vars')->insert($insertBatch);
                }
            });
    }

    public function down(): void
    {
        // Remove product_vars created from products_collections
        $ids = DB::table('products_collections')->pluck('id');
        if($ids->count() > 0){
            DB::table('product_vars')->whereIn('product_id', $ids)->delete();
        }
    }
};

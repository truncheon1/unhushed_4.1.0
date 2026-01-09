<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateCompletedCartsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:completed-carts
                            {--dry-run : Run the migration without actually inserting data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate completed carts (completed=2) to purchase_carts, purchase_items, and purchase_fulfillments';

    /**
     * Item ID to product/variant mapping
     *
     * @var array
     */
    protected $itemMapping = [
        1 => ['var_id' => 15, 'product_id' => 15],
        2 => ['var_id' => 94, 'product_id' => 53],
        3 => ['var_id' => 17, 'product_id' => 17],
        5 => ['var_id' => 91, 'product_id' => 50],
        6 => ['var_id' => 20, 'product_id' => 21],
        7 => ['var_id' => 21, 'product_id' => 22],
        8 => ['var_id' => 74, 'product_id' => 15],
        //Cart 71: item_id 8
        9 => ['var_id' => 22, 'product_id' => 23],
        10 => ['var_id' => 97, 'product_id' => 24],
        11 => ['var_id' => 99, 'product_id' => 15], 
        // Cart 65: item_id 11 Sex Ed Saves Lives T Shirt-2xl blue
        // Cart 1999: item_id Sex Ed Saves Lives T Shirt-2xl blue
        12 => ['var_id' => 24, 'product_id' => 25],
        14 => ['var_id' => 26, 'product_id' => 27],
        16 => ['var_id' => 82, 'product_id' => 29],
        19 => ['var_id' => 63, 'product_id' => 23],
        20 => ['var_id' => 27, 'product_id' => 29],
        21 => ['var_id' => 83, 'product_id' => 22],
        22 => ['var_id' => 87, 'product_id' => 21],
        23 => ['var_id' => 32, 'product_id' => 36],
        24 => ['var_id' => 79, 'product_id' => 36],
        25 => ['var_id' => 56, 'product_id' => 25],
        26 => ['var_id' => 80, 'product_id' => 27],
        27 => ['var_id' => 36, 'product_id' => 40],
        28 => ['var_id' => 37, 'product_id' => 41],
        29 => ['var_id' => 81, 'product_id' => 42],
        30 => ['var_id' => 38, 'product_id' => 42],
        31 => ['var_id' => 45, 'product_id' => 49],
        32 => ['var_id' => 47, 'product_id' => 51],
        33 => ['var_id' => 48, 'product_id' => 52],
        //Cart 2933: item_id 34
        //Cart 2963: item_id 34
        35 => ['var_id' => 95, 'product_id' => 53],
        37 => ['var_id' => 52, 'product_id' => 58],
        138 => ['var_id' => 101, 'product_id' => 50],
        //Cart 1929: item_id 138 Storks make storks T shirt-Heather Black 3xl
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no data will be inserted');
        }

        try {
            // Get all completed carts
            $carts = DB::table('purchase_carts')
                ->where('completed', 2)
                ->get();

            if ($carts->isEmpty()) {
                $this->info('No completed carts found (completed=2).');
                return 0;
            }

            $this->info("Found {$carts->count()} completed carts to migrate.");

            $migratedCarts = 0;
            $migratedItems = 0;
            $skippedCarts = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($carts as $cart) {
                try {
                    // Check if cart already migrated
                    $existingCart = DB::table('purchase_carts')
                        ->where('id', $cart->id)
                        ->exists();

                    if ($existingCart) {
                        $skippedCarts++;
                        $this->warn("Skipped: Cart {$cart->id} already migrated");
                        continue;
                    }

                    // Get cart items
                    $cartItems = DB::table('purchase_items')
                        ->where('cart_id', $cart->id)
                        ->get();

                    // Validate all items have mappings before proceeding
                    foreach ($cartItems as $item) {
                        if (!isset($this->itemMapping[$item->item_id])) {
                            throw new Exception("No mapping found for item_id {$item->item_id} in cart {$cart->id}");
                        }
                    }

                    // 1. Insert into purchase_carts
                    if (!$dryRun) {
                        DB::table('purchase_carts')->insert([
                            'id' => $cart->id,
                            'user_id' => $cart->user_id,
                            'payment_id' => $cart->pp_id ?? null,
                            'completed' => 2,
                            'tax' => $cart->tax ?? 0,
                            'shipping' => $cart->shipping ?? 0,
                            'total' => $cart->total ?? 0,
                            'completed_at' => $cart->created_at,
                            'created_at' => $cart->created_at,
                            'updated_at' => now(),
                        ]);
                    }

                    // 2. Create fulfillment record (address migration handled separately)
                    // Insert into purchase_fulfillments
                    if (!$dryRun) {
                        DB::table('purchase_fulfillments')->insert([
                            'cart_id' => $cart->id,
                            'address_id' => null, // Will be filled by migrate:shipping-addresses command
                            'status' => $cart->status ?? 0,
                            'shipped' => $cart->shipped ?? null,
                            'tracking' => $cart->tracking ?? null,
                            'notes' => 'migrated from old system',
                            'created_at' => $cart->created_at,
                            'updated_at' => now(),
                        ]);
                    }

                    // 3. Insert cart items into purchase_items
                    foreach ($cartItems as $item) {
                        $mapping = $this->itemMapping[$item->item_id];

                        if (!$dryRun) {
                            DB::table('purchase_items')->insert([
                                'id' => $item->id,
                                'cart_id' => $cart->id,
                                'product_id' => $mapping['product_id'],
                                'var_id' => $mapping['var_id'],
                                'quantity' => $item->qty,
                                'price' => $item->cost,
                            ]);
                        }

                        $migratedItems++;
                    }

                    $migratedCarts++;
                    
                    if ($migratedCarts % 10 == 0) {
                        $this->info("Migrated {$migratedCarts} carts with {$migratedItems} items...");
                    }

                } catch (Exception $e) {
                    $errors[] = "Cart {$cart->id}: {$e->getMessage()}";
                    $skippedCarts++;
                    continue;
                }
            }

            if (!$dryRun) {
                DB::commit();
                $this->info("\n✓ Migration completed successfully!");
            } else {
                DB::rollBack();
                $this->info("\n✓ Dry run completed - no data was inserted");
            }

            $this->newLine();
            $this->info("Summary:");
            $this->info("Total carts processed: {$carts->count()}");
            $this->info("Successfully migrated carts: {$migratedCarts}");
            $this->info("Successfully migrated items: {$migratedItems}");
            $this->info("Skipped carts: {$skippedCarts}");

            if (!empty($errors)) {
                $this->newLine();
                $this->error("Errors:");
                foreach ($errors as $error) {
                    $this->error("  - {$error}");
                }
                return 1;
            }

            return 0;

        } catch (Exception $e) {
            DB::rollBack();
            $this->error("Migration failed: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}

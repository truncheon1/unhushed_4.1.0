<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class MigrateShippingAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:shipping-addresses
                            {--dry-run : Run the migration without actually updating data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate shipping addresses from carts to user_addresses and update purchase_fulfillments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no data will be inserted or updated');
        }

        try {
            // Get all shipping addresses for completed carts
            $shippingAddresses = DB::table('shipping_addresses')
                ->join('purchase_carts', 'shipping_addresses.cart_id', '=', 'purchase_carts.id')
                ->where('purchase_carts.completed', 2)
                ->select('shipping_addresses.*', 'purchase_carts.user_id')
                ->get();

            if ($shippingAddresses->isEmpty()) {
                $this->info('No shipping addresses found for completed carts.');
                return 0;
            }

            $this->info("Found {$shippingAddresses->count()} shipping addresses for completed carts.");

            $migrated = 0;
            $skipped = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($shippingAddresses as $shippingAddress) {
                try {
                    $cartId = $shippingAddress->cart_id;

                    // Check if purchase_fulfillment already has an address
                    $fulfillment = DB::table('purchase_fulfillments')
                        ->where('cart_id', $cartId)
                        ->first();

                    if (!$fulfillment) {
                        $errors[] = "Cart {$cartId}: No purchase_fulfillment found";
                        $skipped++;
                        continue;
                    }

                    if ($fulfillment->address_id) {
                        $this->info("Cart {$cartId}: Already has address_id {$fulfillment->address_id}");
                        $skipped++;
                        continue;
                    }

                    // Get user email
                    $user = DB::table('users')
                        ->where('id', $shippingAddress->user_id)
                        ->first();

                    if (!$user) {
                        $errors[] = "Cart {$cartId}: User {$shippingAddress->user_id} not found";
                        $skipped++;
                        continue;
                    }

                    // Build street address
                    $address1 = $shippingAddress->api_address1 ?? '';
                    $address2 = $shippingAddress->api_address2 ?? '';
                    $street = trim($address1 . ' ' . $address2);
                    
                    $name = $shippingAddress->name ?? '';
                    $city = $shippingAddress->api_city ?? '';
                    $zip = $shippingAddress->zip ?? '';
                    $state = $shippingAddress->api_state ?? '';

                    $this->info("Processing cart {$cartId} (user {$shippingAddress->user_id})");
                    $this->info("  Address: {$name}, {$street}, {$city}, {$state} {$zip}");

                    // Check if similar address already exists for this user
                    $existingAddress = null;
                    if (!empty($zip) && !empty($street)) {
                        $existingAddress = DB::table('user_addresses')
                            ->where('user_id', $shippingAddress->user_id)
                            ->where('zip', $zip)
                            ->where('street', $street)
                            ->first();
                    }

                    $addressId = null;

                    if ($existingAddress) {
                        $this->info("  Reusing existing address {$existingAddress->id}");
                        $addressId = $existingAddress->id;
                    } else {
                        // Insert new address
                        if (!$dryRun) {
                            $addressId = DB::table('user_addresses')->insertGetId([
                                'user_id' => $shippingAddress->user_id,
                                'default' => 0,
                                'name' => $name ?: null,
                                'company' => null,
                                'email' => $user->email ?? null,
                                'phone' => null,
                                'street' => $street ?: null,
                                'city' => $city ?: null,
                                'zip' => $zip ?: null,
                                'county' => null,
                                'state_province' => $state ?: null,
                                'country' => 'US',
                                'created_at' => $shippingAddress->created_at ?? now(),
                                'updated_at' => now(),
                            ]);

                            $this->info("  Created new address {$addressId}");
                        } else {
                            $this->info("  [Dry-run] Would create new address");
                        }
                    }

                    // Update purchase_fulfillments with address_id
                    if (!$dryRun && $addressId) {
                        DB::table('purchase_fulfillments')
                            ->where('cart_id', $cartId)
                            ->update([
                                'address_id' => $addressId,
                                'updated_at' => now(),
                            ]);

                        $this->info("  Updated fulfillment for cart {$cartId} with address_id {$addressId}");
                    }

                    $migrated++;

                    if ($migrated % 10 == 0) {
                        $this->info("Processed {$migrated} addresses...");
                    }

                } catch (Exception $e) {
                    $errors[] = "Cart {$shippingAddress->cart_id}: {$e->getMessage()}";
                    $skipped++;
                    continue;
                }
            }

            if (!$dryRun) {
                DB::commit();
                $this->info("\n✓ Migration completed successfully!");
            } else {
                DB::rollBack();
                $this->info("\n✓ Dry run completed - no data was inserted or updated");
            }

            $this->newLine();
            $this->info("Summary:");
            $this->info("Total shipping addresses processed: {$shippingAddresses->count()}");
            $this->info("Successfully migrated: {$migrated}");
            $this->info("Skipped: {$skipped}");

            if (!empty($errors)) {
                $this->newLine();
                $this->error("Errors:");
                foreach ($errors as $error) {
                    $this->error("  - {$error}");
                }
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

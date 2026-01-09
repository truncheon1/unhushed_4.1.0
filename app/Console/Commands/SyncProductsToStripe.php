<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Products;
use App\Models\ProductVar;
use App\Models\CurriculumPrice;
use Laravel\Cashier\Cashier;

class SyncProductsToStripe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:sync-products 
                            {--live : Use live Stripe keys instead of test mode}
                            {--product= : Sync only a specific product ID}
                            {--dry-run : Show what would be created without actually creating}
                            {--force : Re-sync products that already have Stripe IDs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync product catalog to Stripe (creates Products and Prices)';

    private $stripe;
    private $isDryRun = false;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->isDryRun = $this->option('dry-run');
        
        if ($this->option('live')) {
            $this->warn('🔴 Using LIVE Stripe mode!');
            if (!$this->confirm('Are you sure you want to sync to LIVE Stripe?')) {
                $this->info('Cancelled.');
                return 0;
            }
        } else {
            $this->info('🧪 Using TEST Stripe mode');
        }

        if ($this->isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No changes will be made to Stripe');
        }

        $this->stripe = Cashier::stripe();

        if ($productId = $this->option('product')) {
            $products = Products::where('id', $productId)->get();
            if ($products->isEmpty()) {
                $this->error("Product ID {$productId} not found!");
                return 1;
            }
        } else {
            $query = Products::with('vars')->orderBy('category')->orderBy('id');
            
            // Only sync products without Stripe IDs unless --force is used
            if (!$this->option('force')) {
                $query->whereNull('stripe_product_id');
            }
            
            $products = $query->get();
        }

        $this->info("Found {$products->count()} products to sync");
        $bar = $this->output->createProgressBar($products->count());

        $synced = 0;
        $errors = 0;

        foreach ($products as $product) {
            try {
                $this->syncProduct($product);
                $synced++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error syncing product {$product->id} ({$product->name}): {$e->getMessage()}");
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        if ($this->isDryRun) {
            $this->info("✅ Dry run complete! Would have synced {$synced} products");
        } else {
            $this->info("✅ Synced {$synced} products successfully!");
        }
        
        if ($errors > 0) {
            $this->warn("⚠️  {$errors} errors occurred");
        }

        return 0;
    }

    private function syncProduct($product)
    {
        $this->newLine();
        $this->line("📦 Syncing: {$product->name} (ID: {$product->id}, Category: {$product->category})");

        // Validate product before syncing
        if (!$this->validateProduct($product)) {
            return;
        }

        // Create or update Stripe Product
        if ($this->isDryRun) {
            $this->line("  [DRY RUN] Would create Stripe Product");
            $stripeProductId = 'prod_dry_run_' . $product->id;
        } else {
            if ($product->stripe_product_id) {
                $productData = [
                    'name' => $product->name,
                    'metadata' => [
                        'unhushed_product_id' => $product->id,
                        'category' => $product->category,
                    ],
                ];
                
                $description = strip_tags($product->description ?? '');
                if (!empty($description)) {
                    $productData['description'] = $description;
                }
                
                $stripeProduct = $this->stripe->products->update($product->stripe_product_id, $productData);
                $this->line("  ♻️  Updated Stripe Product: {$stripeProduct->id}");
            } else {
                $productData = [
                    'name' => $product->name,
                    'metadata' => [
                        'unhushed_product_id' => $product->id,
                        'category' => $product->category,
                    ],
                ];
                
                $description = strip_tags($product->description ?? '');
                if (!empty($description)) {
                    $productData['description'] = $description;
                }
                
                $stripeProduct = $this->stripe->products->create($productData);
                $product->stripe_product_id = $stripeProduct->id;
                $product->save();
                $this->line("  ✨ Created Stripe Product: {$stripeProduct->id}");
            }
            $stripeProductId = $stripeProduct->id;
        }

        // Handle Category 1 (Curriculum) - needs special pricing tiers
        if ($product->category == 1) {
            $this->syncCurriculumPricing($product, $stripeProductId);
        } else {
            // Sync product variants as prices
            $this->syncProductVariants($product, $stripeProductId);
        }
    }

    private function syncCurriculumPricing($product, $stripeProductId)
    {
        $pricingTiers = CurriculumPrice::where('product_id', $product->id)
            ->where('recurring_price', '>', 0)
            ->get();
        
        if ($pricingTiers->isEmpty()) {
            $this->warn("  ⚠️  No curriculum pricing tiers with price > $0.00!");
            return;
        }

        $this->line("  📊 Found {$pricingTiers->count()} pricing tiers");

        foreach ($pricingTiers as $tier) {
            if ($this->isDryRun) {
                $this->line("    [DRY RUN] Would create Price for {$tier->min_users}-{$tier->max_users} users");
                continue;
            }

            if ($tier->stripe_price_id) {
                $this->line("    ⏭️  Tier {$tier->min_users}-{$tier->max_users} already has Price ID");
                continue;
            }

            // Create recurring price for this tier
            $stripePrice = $this->stripe->prices->create([
                'product' => $stripeProductId,
                'unit_amount' => round($tier->recurring_price * 100), // Convert to cents
                'currency' => 'usd',
                'recurring' => [
                    'interval' => 'year',
                ],
                'metadata' => [
                    'unhushed_tier_id' => $tier->id,
                    'min_users' => $tier->min_users,
                    'max_users' => $tier->max_users,
                    'first_year_price' => $tier->discount_price,
                    'recurring_price' => $tier->recurring_price,
                ],
            ]);

            $tier->stripe_price_id = $stripePrice->id;
            $tier->save();

            $this->line("    ✨ Created Price: {$stripePrice->id} ({$tier->min_users}-{$tier->max_users} users)");
        }
    }

    private function syncProductVariants($product, $stripeProductId)
    {
        $variants = ProductVar::where('product_id', $product->id)
            ->where('avail', 1)
            ->where('price', '>', 0)
            ->get();
        
        if ($variants->isEmpty()) {
            $this->warn("  ⚠️  No available variants with price > $0.00!");
            return;
        }

        $this->line("  🎨 Found {$variants->count()} variants");

        foreach ($variants as $variant) {
            if ($this->isDryRun) {
                $this->line("    [DRY RUN] Would create Price for variant {$variant->var_id}");
                continue;
            }

            if ($variant->stripe_price_id) {
                $this->line("    ⏭️  Variant {$variant->var_id} already has Price ID");
                continue;
            }

            $stripePrice = $this->stripe->prices->create([
                'product' => $stripeProductId,
                'unit_amount' => round($variant->price * 100), // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'unhushed_variant_id' => $variant->var_id,
                    'product_id' => $product->id,
                    'sku' => $variant->sku ?? '',
                    'variant_name' => $variant->variant_name ?? '',
                ],
            ]);

            $variant->stripe_price_id = $stripePrice->id;
            $variant->save();

            $this->line("    ✨ Created Price: {$stripePrice->id} (Variant: {$variant->var_id})");
        }
    }

    private function validateProduct($product)
    {
        // Category 1 (Curriculum) - validate pricing tiers
        if ($product->category == 1) {
            $pricingTiers = CurriculumPrice::where('product_id', $product->id)
                ->where('recurring_price', '>', 0)
                ->get();
            
            if ($pricingTiers->isEmpty()) {
                $this->warn("  ⚠️  SKIPPED: No valid curriculum pricing (all prices are $0.00)");
                return false;
            }
            
            return true;
        }

        // All other categories - validate variants
        $validVariants = ProductVar::where('product_id', $product->id)
            ->where('avail', 1)
            ->where('price', '>', 0)
            ->get();
        
        if ($validVariants->isEmpty()) {
            $this->warn("  ⚠️  SKIPPED: No available variants with price > $0.00");
            return false;
        }

        return true;
    }
}

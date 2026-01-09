# Stripe Production Deployment Instructions

## Overview
This guide will help you deploy the new Stripe pricing structure to your live site. The changes ensure all Stripe price IDs are stored in the correct tables (`curriculum_prices` and `product_vars`) instead of the `products` table.

## Prerequisites
- SSH/Terminal access to production server
- Database backup completed
- Live Stripe API keys configured in `.env`
- All code changes deployed to production

## Deployment Steps

### Step 1: Backup Database
```bash
# Create a backup before making any changes
php artisan db:backup
# OR use your hosting provider's backup tool
```

### Step 2: Run Database Migration
This removes the `stripe_price_id` column from the `products` table:

```bash
php artisan migrate
```

Expected output:
```
2026_01_08_202248_remove_stripe_price_id_from_products_table ........ DONE
```

### Step 3: Clear Existing Stripe IDs (If Needed)
If your production database has Stripe IDs from test mode or a different Stripe account:

```bash
# Check if you need to clear IDs
php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class); \$kernel->bootstrap(); echo 'Products with Stripe IDs: ' . DB::table('products')->whereNotNull('stripe_product_id')->count() . PHP_EOL;"

# If you see products with IDs that need clearing, run:
php clear_stripe_ids.php --execute
php clear_price_ids.php --execute
```

### Step 4: Sync Products to LIVE Stripe
⚠️ **CRITICAL:** This will create products and prices in your LIVE Stripe account using LIVE API keys.

```bash
# First, do a dry run to see what will be created
php artisan stripe:sync-products --live --dry-run

# Review the output carefully, then run for real
php artisan stripe:sync-products --live
```

The command will prompt for confirmation:
```
🔴 Using LIVE Stripe mode!
Are you sure you want to sync to LIVE Stripe? (yes/no) [no]:
> yes
```

Expected results:
- **43 products** will be synced
- **9 curriculum pricing tiers** with recurring prices (category 1)
- **61+ product variant prices** (all other categories)

### Step 5: Verify Sync Success

#### Check Database
```bash
# Verify curriculum prices have Stripe IDs
php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class); \$kernel->bootstrap(); \$count = DB::table('curriculum_prices')->whereNotNull('stripe_price_id')->count(); echo 'Curriculum prices with Stripe IDs: ' . \$count . ' (expected: 9)' . PHP_EOL;"

# Verify product variants have Stripe IDs  
php -r "require 'vendor/autoload.php'; \$app = require_once 'bootstrap/app.php'; \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class); \$kernel->bootstrap(); \$count = DB::table('product_vars')->whereNotNull('stripe_price_id')->count(); echo 'Product variants with Stripe IDs: ' . \$count . ' (expected: 60+)' . PHP_EOL;"
```

#### Check Stripe Dashboard
1. Log into https://dashboard.stripe.com (LIVE mode)
2. Go to **Products** section
3. Verify you see all 43 UNHUSHED products
4. Click on a curriculum product (e.g., "Middle School Curriculum")
5. Verify it has 3 pricing tiers (1-2 users, 3-9 users, 10-1000 users)

### Step 6: Test Subscription Purchase
1. Create a test purchase using a real account (or test account on live site)
2. Add a curriculum subscription to cart
3. Proceed to checkout
4. Use Stripe test card: `4242 4242 4242 4242` (any future expiry, any CVC)
5. Verify checkout completes successfully
6. Check Stripe dashboard for the subscription

## What Changed

### Database Structure
**Before:**
```
products
  ├── stripe_product_id
  └── stripe_price_id ❌ (confusing, not used)
```

**After:**
```
products
  └── stripe_product_id ✅ (parent product only)

curriculum_prices
  └── stripe_price_id ✅ (recurring subscription prices)

product_vars
  └── stripe_price_id ✅ (one-time purchase prices)
```

### Benefits
- ✅ Clear separation: subscriptions vs one-time purchases
- ✅ All products MUST have entries in `product_vars` or `curriculum_prices`
- ✅ No confusion about where price IDs are stored
- ✅ Supports multiple pricing tiers per product (curriculum)
- ✅ Supports multiple variants per product (physical products)

## Troubleshooting

### Error: "No such price: 'price_xxx'"
**Cause:** Database has price IDs from test mode or different Stripe account  
**Fix:** Run Step 3 to clear and recreate all price IDs

### Error: "No such product: 'prod_xxx'"
**Cause:** Database has product IDs from different Stripe account  
**Fix:** Run `clear_stripe_ids.php --execute` then re-run Step 4

### Products showing $0.00 or missing prices
**Cause:** Price IDs not synced properly  
**Fix:** 
```bash
php artisan stripe:sync-products --live --force
```

### "Payment method not found" error during checkout
**Cause:** SetupIntent didn't attach payment method  
**Fix:** This is a Stripe.js issue - check browser console for errors

## Rollback Plan

If something goes wrong:

1. **Restore database backup**
   ```bash
   # Restore your backup from Step 1
   ```

2. **Roll back migration**
   ```bash
   php artisan migrate:rollback --step=1
   ```

3. **Old system restored** - but you'll need to fix price IDs manually

## Support Scripts Included

The following helper scripts are available:

- `clear_stripe_ids.php` - Clears `stripe_product_id` from products
- `clear_price_ids.php` - Clears price IDs from `curriculum_prices` and `product_vars`
- `check_prices.php` - Displays curriculum pricing data
- `check_order.php` - Debug specific order issues
- `create_stripe_prices.php` - Legacy script (not needed, use artisan command)

## Future Updates

When adding new products or changing prices:

```bash
# Sync new/updated products only (skips existing)
php artisan stripe:sync-products --live

# Force re-sync all products (updates existing)
php artisan stripe:sync-products --live --force

# Sync single product by ID
php artisan stripe:sync-products --live --product=5
```

## Important Notes

⚠️ **Test Mode vs Live Mode**
- Without `--live` flag: Uses Stripe TEST keys (safe for development)
- With `--live` flag: Uses Stripe LIVE keys (production)

⚠️ **Stripe Price IDs are Immutable**
- Once created, Stripe price IDs cannot be changed
- To update pricing: Create new price in Stripe, update database
- Old price IDs remain valid for existing subscriptions

⚠️ **Recurring Prices**
- Only curriculum products (category=1) create recurring prices
- All other products create one-time payment prices
- Recurring interval: 1 year

## Questions or Issues?

If you encounter any issues during deployment:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Stripe dashboard for detailed error messages
3. Verify `.env` has correct LIVE Stripe keys
4. Ensure database migration ran successfully

# UNHUSHED Pricing System Refactoring Plan

## Executive Summary

The UNHUSHED platform currently implements a 3-tier pricing system:
1. **Variant Base Pricing**: `product_vars.price` for single-unit purchases
2. **Bulk Discount Pricing**: `bulk_pricings` table for quantity-based discounts
3. **Curriculum Tier Pricing**: `curriculum_prices` (replacing legacy `packages_options`) for category 1 (curriculum) products with user-range pricing (min/max users, standard / discount / recurring).

**Recent Refactoring (Completed December 2024)**:
- ✅ **Packages table DROPPED** - All curriculum data consolidated into `products` table with `category=1`
- ✅ **Column renames** - `active_subscriptions.package_id` → `product_id`, `type` → `category`
- ✅ **Column renames** - `curriculum_units.package_id` → `product_id`
- ✅ **Model corrections** - All references now use correct `ProductAssignments` (not `PackagesAssignment`)
- ✅ **Packages.php model DELETED** - Use `Products::where('category', 1)` instead
- ✅ **Table name corrections** - All joins use `product_assignments` (not `packages_assignments`)

**Remaining Critical Issues**:
- **ProductsCollection model deleted but still referenced** in 20+ locations across CartProductController, PayPalWebhook, DiscountController, RenewalsController
- **Pricing logic inconsistency**: Some code uses product.price (base), some uses get_price_for_range() (bulk), some uses getOptionForRange() (curriculum)
- **Missing model relationships (historical)**: BulkPricing model still needs enrichment; legacy PackagesOptions replaced by new `CurriculumPrice` model with relationships and casts
- **N+1 query potential**: Bulk pricing not eager-loaded in cart operations
- **Legacy naming confusion**: `collection_id` in bulk_pricings refers to product_id (renamed in migration)

## Current Architecture

### 1. Database Schema

#### products
```sql
id                  BIGINT (PK)
name                VARCHAR
slug                VARCHAR
description         TEXT
ac_tags             VARCHAR (nullable)
category            INTEGER (0=unknown, 1=curriculum, 2=activity, 3=book, 4=game, 5=swag, 6=toolkit, 7=training)
sort                INTEGER
created_at/updated_at
```

#### product_vars
```sql
var_id              BIGINT (PK)
product_id          BIGINT (FK → products.id)
sku                 VARCHAR
deliver_slug        VARCHAR (digital delivery)
description2        TEXT
price               FLOAT (BASE UNIT PRICE)
taxable             TINYINT (0=no, 1=yes)
ship_type           INTEGER (0=digital, 1=standard, 2=media mail)
weight              FLOAT (PP.OO format - lbs.oz combined)
qty                 INTEGER (stock quantity)
avail               TINYINT (1=available, 0=hidden)
paypal_id           VARCHAR
created_at/updated_at
```

#### bulk_pricings
```sql
id                  BIGINT (PK)
collection_id       BIGINT (FK → products.id, legacy name for product_id)
up_to               INTEGER (quantity threshold - price applies when qty >= up_to)
price               FLOAT (unit price when threshold met)
created_at/updated_at
```

**Bulk Pricing Logic**: Query orders by `up_to DESC` and returns first match where qty >= up_to. Falls back to product_vars.price if no match.

#### packages (DROPPED December 2024)
```sql
-- ❌ TABLE NO LONGER EXISTS
-- Previously: id, name, slug, product_id, language_id, currency_id, etc.
-- Replacement: Use products table with category=1 for curriculum items
```

**Migration History:**
- `2025_12_09_202828_drop_packages_table.php` - Dropped packages table entirely
- All curriculum data now in `products` where `category = 1`
- Use `Products::where('category', 1)` instead of `Packages::` queries

#### curriculum_prices (replaces legacy packages_options)
```sql
id                  BIGINT (PK)
product_id          BIGINT (FK → products.id, cascade delete)  -- curriculum product (category = 1)
min_users           INTEGER (default 0)
max_users           INTEGER (default 1)
standard_price      FLOAT (default 0)
discount_price      FLOAT (default 0)  -- first year price
recurring_price     FLOAT (default 0)  -- renewal price
package_caption     VARCHAR            -- marketing label
created_at/updated_at
```

**Curriculum Pricing Logic**: Query where `min_users <= qty AND max_users >= qty` on `curriculum_prices.product_id`. Used exclusively for products with `category = 1` (curriculum). Legacy `PackagesOptions` rows are deprecated.

### 2. Model Implementation

#### Products.php (app/Models/Products.php)
```php
public function bulk_pricings(){
    return $this->hasMany('App\Models\BulkPricing', 'collection_id')->orderBy('up_to', 'ASC');
}

public function get_price_for_range($num){
    $price = BulkPricing::where('collection_id', $this->id)
        ->where('up_to', '<=', $num)
        ->orderBy('up_to', 'DESC')
        ->first();
    return $price ? $price->price : $this->price;
}

public function vars(){
    return $this->hasMany(ProductVar::class, 'product_id');
}

// Recently added scopes (from previous refactoring):
public function scopeAvailable($query) // filters by variant avail + legacy rules
public function minAvailablePrice() // returns min variant price or product.price fallback
public function getDisplayPriceAttribute() // accessor: "FREE" or "$ X.XX"
```

**Critical Issue**: `get_price_for_range()` uses `$this->price` as fallback, but Products table has NO price column! This was removed during migration from products_collections. Should fall back to `$this->vars()->where('avail', 1)->min('price')`.

#### Packages.php (DELETED December 2024)
```php
// ❌ MODEL NO LONGER EXISTS
// app/Models/Packages.php was deleted as part of packages table removal

// ✅ REPLACEMENT: Use Products model with category filter
$curriculum = Products::where('category', 1)->find($id);

// ✅ REPLACEMENT: Get curriculum pricing tiers
$pricingOptions = CurriculumPrice::where('product_id', $productId)
    ->orderBy('min_users', 'ASC')
    ->get();

// ✅ REPLACEMENT: Get pricing for specific user count
$pricing = CurriculumPrice::where('product_id', $productId)
    ->where('min_users', '<=', $userCount)
    ->where('max_users', '>=', $userCount)
    ->first();

// ✅ Curriculum-specific helper methods moved to ProductAssignments model:
// - ProductAssignments::userHasCurriculum($userId, $productId)
// - ProductAssignments::curriculumByName($name)
// - ProductAssignments::elementary()
// - ProductAssignments::middle()
// - ProductAssignments::high()
// - ProductAssignments::free()
// - ProductAssignments::unitsFor($productId)
```

#### BulkPricing.php (app/Models/BulkPricing.php)
```php
class BulkPricing extends Model
{
    use HasFactory;
}
```

**Critical Issue**: Empty model with no fillable, relationships, or logic. Should define:
- `belongsTo(Products::class, 'collection_id')`
- `$fillable` array
- Validation rules

#### CurriculumPrice.php (app/Models/CurriculumPrice.php)
```php
class CurriculumPrice extends Model
{
    use HasFactory;
    protected $table = 'curriculum_prices';
    protected $fillable = [
        'product_id','min_users','max_users','standard_price',
        'discount_price','recurring_price','package_caption'
    ];
    protected $casts = [
        'min_users'=>'integer','max_users'=>'integer',
        'standard_price'=>'float','discount_price'=>'float','recurring_price'=>'float'
    ];
    public function product(){ return $this->belongsTo(Products::class,'product_id'); }
    public function scopeForUsers($q,$n){ return $q->where('min_users','<=',$n)->where('max_users','>=',$n); }
}
```

Replaces legacy PackagesOptions; relationships and casting now implemented.

### 3. Pricing Usage Flow

#### Cart Add (CartProductController::add_to_cart)
```php
// Line 80: Find "collection" (actually just a product)
$collection = Products::where('ref_id', $req->input('item_id'))
    ->where('type', $product->type)
    ->first();

// Lines 88, 96: Calculate cost using bulk pricing
$cartItem->cost = $collection->get_price_for_range($qty) * $qty;
```

**Critical Issues**:
1. `ref_id` column doesn't exist in products table (remnant from old schema)
2. `type` column doesn't exist (replaced by `category`)
3. This query will ALWAYS return null, causing "Collection not found" errors

#### Cart Display (CartProductController::cart_products)
```php
// Line 174: Tries to use ProductsCollection model (DELETED!)
$collection = ProductsCollection::where('reference_id', $item->item_id)
    ->where('type', $product->type)
    ->first();

// Line 182: Calculate price
$row['price'] = $collection->get_price_for_range($item->qty);
```

**Critical Issue**: Fatal error - class ProductsCollection not found.

#### Curriculum Add (CartSubscriptController::add_to_cart)
```php
// Lines 115-116: Package pricing (NEEDS UPDATE - Task 4 deferred)
// ❌ Current code still references deleted Packages model:
$package = Packages::find($id); // WILL FAIL - model deleted
$option = $package->getOptionForRange($totalQuantity);
$price = $option->discount_price * $req->input('qty')[$package->id];

// ✅ Should be updated to:
$product = Products::where('category', 1)->find($id);
$pricing = CurriculumPrice::where('product_id', $product->id)
    ->where('min_users', '<=', $totalQuantity)
    ->where('max_users', '>=', $totalQuantity)
    ->first();
$price = $pricing->discount_price * $req->input('qty')[$product->id];
```

**Status**: Deferred per user request (Task 4). Currently broken but not in active use.

#### PayPal Webhook (PayPalWebhook.php)
```php
// Line 198: Uses ProductsCollection (DELETED!)
$prod_collection = \App\Models\ProductsCollection::where('type', $product->type)
    ->where('reference_id', $product->id)
    ->first();
```

**Critical Issue**: Webhook processing will fail for completed orders.

## Completed Refactoring (December 2024)

### Packages Table Removal
**Goal**: Consolidate curriculum data from deprecated `packages` table into `products` table using `category=1`.

**Completed Work**:
1. ✅ Created and ran migration `2025_12_09_202828_drop_packages_table.php`
2. ✅ Updated 8+ controllers to use `Products::where('category', 1)` instead of `Packages::`
3. ✅ Moved curriculum helper methods from Packages to ProductAssignments model
4. ✅ Deleted `app/Models/Packages.php` model file
5. ✅ Updated User model relationships and license methods
6. ✅ Fixed all Blade views to use Products model

### Active Subscriptions Table Updates
**Goal**: Rename columns to reflect products table consolidation.

**Completed Work**:
1. ✅ Created and ran migration `2025_12_09_203000_rename_active_subscriptions_columns.php`
   - Renamed `package_id` → `product_id`
   - Renamed `type` → `category`
   - Updated data: category 0→1 (curriculum), 4→7 (training)
2. ✅ Updated ActiveSubscriptions model to use new column names
3. ✅ Updated 6 controllers: SchoolController, CSVFileController, OrgController, BackendController, PayPalWebhook, ProductsController
4. ✅ Updated 5 Blade views: assign-package, assign-training, org, user-profile, users

### Curriculum Units Table Updates
**Goal**: Rename package_id column to match products table.

**Completed Work**:
1. ✅ Created and ran migration `2025_12_09_204500_rename_curriculum_units_package_id_column.php`
   - Renamed `package_id` → `product_id`
2. ✅ Updated 5 files: ProductAssignments model, PackagesController, ProductsController, DownloadController

### Model Name Corrections
**Goal**: Fix incorrect model references throughout codebase.

**Completed Work**:
1. ✅ Fixed 6 locations using incorrect `PackagesAssignment` (should be `ProductAssignments`)
2. ✅ Updated join table references from `packages_assignments` to `product_assignments`
3. ✅ Files corrected: UserProfileController, TrainingController, ProductsController (2 methods), PayPalWebhook (2 locations)
4. ✅ Updated config/curricula.php documentation comment
5. ✅ Verified no incorrect references in JavaScript, tests, seeders, or production views

### Deferred Work
- ⏸️ **Task 4**: CartSubscriptController still has 3 references to deleted Packages model (lines 109, 169, 188)
  - Deferred per user request - not currently in active use
  - Missing `use App\Models\Packages` import would cause fatal error if executed

## Issues Analysis

### Priority 1: Critical Bugs (System Breaking)

1. **ProductsCollection class not found**
   - **Impact**: Fatal error in cart display, checkout, webhooks, renewals, discount management
   - **Affected Files**: 
     - `app/Http/Controllers/Store/CartProductController.php` (8 references)
     - `app/Http/Controllers/Store/PayPalWebhook.php` (3 references)
     - `app/Http/Controllers/Finance/DiscountController.php` (3 references)
     - `app/Http/Controllers/Finance/RenewalsController.php` (2 references)
   - **Root Cause**: Migration removed model but didn't update controllers
   - **Fix**: Replace with Products model queries

2. **ref_id and type columns don't exist**
   - **Impact**: "Collection not found" errors when adding products to cart
   - **Location**: `CartProductController::add_to_cart` line 80
   - **Fix**: Remove the collection lookup entirely - use the $product directly

3. **Products.get_price_for_range() fallback broken**
   - **Impact**: Error when no bulk pricing exists: "price" column not found
   - **Location**: `Products.php` line 77
   - **Fix**: Fallback to `$this->minAvailablePrice()` instead of `$this->price`

### Priority 2: Logic Inconsistencies

1. **Pricing precedence unclear**
   - Base variant price vs bulk pricing: Which wins?
   - Current: Bulk pricing overrides variant price completely
   - Issue: No validation that bulk price < base price
   - Could have bulk "discount" that's actually more expensive

2. **Category 1 special treatment**
    - Only curriculum uses curriculum_prices (user-count tiers)
    - Potential future extension: generalize tiered user pricing to other categories via shared pricing_tiers abstraction.

3. **Mixed pricing sources in display**
   - Storefront uses `minAvailablePrice()` (variant-based)
   - Cart uses `get_price_for_range()` (bulk-based)
   - Potential mismatch between listed price and cart price

### Priority 3: Performance Issues

1. **N+1 query in cart display**
   - Line 174: Query per cart item to get bulk pricing
   - Fix: Eager load `bulk_pricings` relationship

2. **Bulk pricing queries not optimized**
   - Multiple queries with `orderBy DESC` then `first()`
   - Could use a single query with grouped results

3. **No caching**
   - Pricing recalculated on every page load
   - Package options recalculated for every cart item
   - Could cache pricing tiers by product_id

### Priority 4: Data Integrity

1. **Orphaned bulk_pricings possible**
   - No foreign key constraint on collection_id
   - Products could be deleted without cleaning up bulk pricing
   - Fix: Add foreign key with cascade

2. **Overlapping curriculum_prices tiers**
   - No unique constraint on min_users/max_users ranges
   - Could have conflicting ranges: (1-10) and (5-15)
   - getOptionForRange() would return arbitrary result

3. **Model completeness**
    - BulkPricing still lacks fillable/validation; CurriculumPrice now implemented.

## Proposed Solution

### Phase 1: Critical Bug Fixes (Immediate - Week 1)

#### 1.1 Remove ProductsCollection References
Replace all ProductsCollection queries with Products model:

```php
// OLD (broken):
$collection = ProductsCollection::where('reference_id', $product->id)->first();
$price = $collection->get_price_for_range($qty);

// NEW:
$product = Products::with('bulk_pricings')->find($product_id);
$price = $product->get_price_for_range($qty);
```

**Files to update**:
- CartProductController.php (8 locations)
- PayPalWebhook.php (3 locations)
- DiscountController.php (3 locations)
- RenewalsController.php (2 locations)

#### 1.2 Fix add_to_cart Collection Lookup
Remove the broken collection query:

```php
// OLD (lines 80-83):
$collection = Products::where('ref_id', $req->input('item_id'))
    ->where('type', $product->type)
    ->first();
if(!$collection) return redirect()->back()->with('status', 'Collection not found!');

// NEW:
// $product already loaded on line 77, just use it directly
```

#### 1.3 Fix get_price_for_range() Fallback
```php
// OLD (Products.php line 77):
return $this->price;

// NEW:
return $this->minAvailablePrice() ?? 0;
```

### Phase 2: Model Improvements (Week 2)

#### 2.1 Add BulkPricing Model Relationships
```php
class BulkPricing extends Model
{
    use HasFactory;

    protected $fillable = ['collection_id', 'up_to', 'price'];

    protected $casts = [
        'up_to' => 'integer',
        'price' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Products::class, 'collection_id');
    }

    // Validation
    public static function rules()
    {
        return [
            'collection_id' => 'required|exists:products,id',
            'up_to' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ];
    }
}
```

#### 2.2 Add CurriculumPrice Model (implemented)
Already added (see snippet above). Next improvement: enforce non-overlapping ranges via composite unique index `(product_id, min_users, max_users)`.

#### 2.3 Add Database Constraints
```php
// Migration: add_bulk_pricing_foreign_key.php
Schema::table('bulk_pricings', function (Blueprint $table) {
    $table->foreign('collection_id')
        ->references('id')
        ->on('products')
        ->onDelete('cascade');
});

// Migration: add_curriculum_prices_constraints.php
Schema::table('curriculum_prices', function (Blueprint $table) {
    $table->index(['product_id', 'min_users', 'max_users']);
    // Future: $table->unique(['product_id','min_users','max_users']);
});
```

### Phase 3: Unified Pricing Strategy (Week 3-4)

#### 3.1 Create PricingService Class
Centralize all pricing logic:

```php
namespace App\Services;

class PricingService
{
    /**
     * Get unit price for a product at specified quantity.
     * Precedence: bulk_pricings > product_vars.price
     */
    public static function getUnitPrice(Products $product, int $qty = 1): float
    {
        // Check bulk pricing first
        if ($bulkPrice = $product->getBulkPriceForQty($qty)) {
            return $bulkPrice;
        }

        // Fall back to minimum variant price
        return $product->minAvailablePrice() ?? 0;
    }

    /**
     * Get total price for product at quantity.
     */
    public static function getTotalPrice(Products $product, int $qty = 1): float
    {
        return self::getUnitPrice($product, $qty) * $qty;
    }

    /**
     * Get pricing for curriculum package.
     */
    public static function getPackagePrice(Packages $package, int $userQty): ?float
    {
        $option = $package->getOptionForRange($userQty); // now backed by curriculum_prices
        return $option?->discount_price;
    }

    /**
     * Calculate cart total with all items.
     */
    public static function calculateCartTotal(Cart $cart): float
    {
        $total = 0;
        foreach ($cart->items as $item) {
            if ($item->item_type == CartItem::TYPE_PRODUCT) {
                $product = Products::find($item->item_id);
                $total += self::getTotalPrice($product, $item->qty);
            }
            // ... handle other item types
        }
        return $total;
    }
}
```

#### 3.2 Add Products Model Method
```php
public function getBulkPriceForQty(int $qty): ?float
{
    $pricing = $this->bulk_pricings()
        ->where('up_to', '<=', $qty)
        ->orderBy('up_to', 'DESC')
        ->first();

    return $pricing?->price;
}
```

#### 3.3 Refactor CartProductController
Replace direct pricing logic with service:

```php
use App\Services\PricingService;

public function add_to_cart(Request $req, $path = 'educators')
{
    // ... validation and cart setup ...

    $product = Products::with('bulk_pricings')->find($req->input('item_id'));
    if(!$product) {
        return redirect()->back()->with('status', 'Product not found!');
    }

    // Add or update cart item
    $cartItem = CartItem::firstOrNew([
        'item_type' => CartItem::TYPE_PRODUCT,
        'cart_id' => $cart->id,
        'item_id' => $product->id,
    ]);

    $cartItem->qty = ($cartItem->exists ? $cartItem->qty : 0) + $req->input('qty');
    $cartItem->cost = PricingService::getTotalPrice($product, $cartItem->qty);
    $cartItem->save();

    return redirect()->to($path.'/cart_products')->with('path', get_path($path));
}
```

### Phase 4: Performance Optimization (Week 5)

#### 4.1 Eager Load Relationships
```php
// In cart display:
$cart = Cart::with([
    'items.product.bulk_pricings',
    'items.product.vars' => fn($q) => $q->where('avail', 1)
])->find($cart_id);
```

#### 4.2 Add Pricing Cache
```php
use Illuminate\Support\Facades\Cache;

public static function getUnitPrice(Products $product, int $qty = 1): float
{
    $cacheKey = "pricing:{$product->id}:{$qty}";
    
    return Cache::remember($cacheKey, 3600, function() use ($product, $qty) {
        // ... pricing logic ...
    });
}
```

#### 4.3 Add Database Indexes
```php
// Migration: add_pricing_indexes.php
Schema::table('bulk_pricings', function (Blueprint $table) {
    $table->index(['collection_id', 'up_to']);
});

Schema::table('product_vars', function (Blueprint $table) {
    $table->index(['product_id', 'avail', 'price']);
});
```

## Testing Strategy

### Unit Tests
```php
// tests/Unit/PricingServiceTest.php
public function test_basic_variant_pricing()
{
    $product = Products::factory()->create();
    $variant = ProductVar::factory()->create([
        'product_id' => $product->id,
        'price' => 10.00,
        'avail' => 1,
    ]);

    $this->assertEquals(10.00, PricingService::getUnitPrice($product, 1));
}

public function test_bulk_pricing_overrides_variant()
{
    $product = Products::factory()->create();
    ProductVar::factory()->create(['product_id' => $product->id, 'price' => 10.00]);
    BulkPricing::factory()->create([
        'collection_id' => $product->id,
        'up_to' => 5,
        'price' => 8.00,
    ]);

    $this->assertEquals(10.00, PricingService::getUnitPrice($product, 1));
    $this->assertEquals(8.00, PricingService::getUnitPrice($product, 5));
}
```

### Integration Tests
```php
// tests/Feature/CartPricingTest.php
public function test_add_product_to_cart_uses_bulk_pricing()
{
    $product = Products::factory()->create();
    BulkPricing::factory()->create([
        'collection_id' => $product->id,
        'up_to' => 10,
        'price' => 7.50,
    ]);

    $response = $this->post('/educators/add_to_cart', [
        'item_id' => $product->id,
        'qty' => 10,
    ]);

    $cartItem = CartItem::where('item_id', $product->id)->first();
    $this->assertEquals(75.00, $cartItem->cost); // 10 * 7.50
}
```

### Manual Testing Checklist
- [ ] Add product to cart (qty=1) → uses variant price
- [ ] Add product to cart (qty=10) → uses bulk price if available
- [ ] Update cart quantity → recalculates with correct tier
- [ ] Add curriculum to cart → uses curriculum_prices tier pricing
- [ ] Complete PayPal checkout → webhook processes correctly
- [ ] Apply discount code → adjusts pricing correctly
- [ ] View cart → displays correct prices without errors

## Migration Path

### Step 1: Prepare (Day 1)
1. Backup database
2. Run test suite baseline
3. Create feature flag: `ENABLE_NEW_PRICING` (default false)

### Step 2: Deploy Phase 1 Fixes (Day 2-3)
1. Merge ProductsCollection removal PR
2. Deploy to staging
3. Run smoke tests on staging
4. Deploy to production (off-peak hours)
5. Monitor error logs for 24 hours

### Step 3: Deploy Phase 2 Models (Day 4-5)
1. Merge model improvements PR
2. Run migrations on staging
3. Test bulk pricing and package pricing forms
4. Deploy to production
5. Verify data integrity queries

### Step 4: Deploy Phase 3 Service (Week 2)
1. Enable feature flag on staging
2. Run full integration test suite
3. Load test cart operations
4. Enable for 10% of production traffic (canary)
5. Monitor performance metrics
6. Gradually increase to 100%

### Step 5: Deploy Phase 4 Performance (Week 3)
1. Add indexes on staging
2. Benchmark query performance
3. Enable caching on staging
4. Test cache invalidation
5. Deploy to production
6. Monitor cache hit rates

## Rollback Strategy

Each phase has independent rollback:

**Phase 1**: Revert code only (no DB changes)
```bash
git revert <commit-hash>
php artisan cache:clear
```

**Phase 2**: Revert migrations
```bash
php artisan migrate:rollback --step=1
git revert <commit-hash>
```

**Phase 3**: Disable feature flag
```bash
# In .env
ENABLE_NEW_PRICING=false
php artisan config:cache
```

**Phase 4**: Drop indexes, disable cache
```bash
php artisan migrate:rollback
php artisan cache:clear
```

## Success Metrics

### Performance
- Cart display load time: < 200ms (currently ~500ms)
- Add to cart latency: < 100ms (currently ~150ms)
- Database queries per cart page: < 10 (currently ~25)

### Reliability
- Zero ProductsCollection errors after Phase 1
- Zero pricing calculation errors
- 100% webhook success rate

### Code Quality
- Test coverage: >80% for pricing logic
- Zero N+1 queries in cart operations
- All models have relationships and validation

## Future Considerations

### Unified Pricing Table
Consider consolidating bulk_pricings and curriculum_prices:

```sql
CREATE TABLE pricing_tiers (
    id BIGINT PRIMARY KEY,
    product_id BIGINT,  -- FK to products
    min_qty INTEGER,
    max_qty INTEGER,
    unit_price FLOAT,
    tier_type ENUM('standard', 'bulk', 'curriculum'),
    metadata JSON,  -- for package_caption, recurring_price, etc.
    created_at/updated_at
);
```

Benefits:
- Single source of truth for all pricing
- Easier to add new pricing types
- Consistent query patterns
- Better data integrity

### Pricing Rules Engine
Consider adding rule-based pricing:
- Time-based discounts (seasonal sales)
- User-type pricing (educators vs organizations)
- Bundle pricing (buy 3 get 1 free)
- Tiered membership discounts

### API for External Pricing
Consider exposing pricing as API:
- `/api/products/{id}/price?qty=10`
- Cache-friendly
- Could power mobile app
- Enables price comparison tools

## Appendix A: File Inventory

### Models
- `app/Models/Products.php` ✅ (has relationships, needs fallback fix)
- `app/Models/ProductAssignments.php` ✅ (correct name, has curriculum helper methods)
- `app/Models/ActiveSubscriptions.php` ✅ (updated to use product_id and category columns)
- `app/Models/BulkPricing.php` ⚠️ (empty, needs relationships)
- `app/Models/Packages.php` ❌ **DELETED** (use Products::where('category', 1) instead)
- `app/Models/CurriculumPrice.php` ✅ (implemented)
- `app/Models/ProductVar.php` ✅ (exists)

### Controllers
- `app/Http/Controllers/Store/CartProductController.php` ❌ (20+ ProductsCollection refs - still needs fixing)
- `app/Http/Controllers/Store/CartSubscriptController.php` ⚠️ (uses deleted Packages model - Task 4 deferred)
- `app/Http/Controllers/Store/PayPalWebhook.php` ⚠️ (3 ProductsCollection refs, but ProductAssignments refs fixed)
- `app/Http/Controllers/Finance/DiscountController.php` ❌ (3 ProductsCollection refs)
- `app/Http/Controllers/Finance/RenewalsController.php` ❌ (2 ProductsCollection refs)
- `app/Http/Controllers/Store/StorefrontController.php` ⚠️ (calc_total_products needs bulk logic)
- `app/Http/Controllers/User/SchoolController.php` ✅ (updated to use Products, ProductAssignments, product_id, category)
- `app/Http/Controllers/User/OrgController.php` ✅ (updated to use Products, ProductAssignments, product_id, category)
- `app/Http/Controllers/User/UserProfileController.php` ✅ (fixed ProductAssignments reference)
- `app/Http/Controllers/Backend/CSVFileController.php` ✅ (updated to use Products, ProductAssignments, product_id, category)
- `app/Http/Controllers/Backend/PackagesController.php` ✅ (updated to use ProductAssignments methods)
- `app/Http/Controllers/Backend/TrainingController.php` ✅ (fixed ProductAssignments reference)
- `app/Http/Controllers/Backend/BackendController.php` ✅ (updated statistics queries for product_id, category)
- `app/Http/Controllers/DownloadController.php` ✅ (uses ProductAssignments::userHasCurriculum)
- `app/Http/Controllers/Store/ProductsController.php` ✅ (fixed join table refs, uses product_id, category)

### Migrations
- `2025_11_13_210756_create_products_table.php` ✅
- `2025_11_13_220000_migrate_products_collections_to_products.php` ✅
- `2025_11_13_220100_migrate_products_collections_to_product_vars.php` ✅
- `2021_12_10_144304_create_bulk_pricings_table.php` ⚠️ (no FK constraint)
- `2025_11_18_123456_create_curriculum_prices_table.php` ✅ (replaces legacy packages_options)

### Views
- `resources/views/backend/products/pricing.blade.php` (bulk pricing management UI)
- `resources/views/store/product/price.blade.php` (curriculum pricing display)

## Appendix B: Query Patterns

### Current (Broken)
```php
// Doesn't work - ProductsCollection deleted
$collection = ProductsCollection::where('reference_id', $product->id)->first();
$price = $collection->get_price_for_range(10);
```

### Phase 1 (Fixed)
```php
$product = Products::find($product_id);
$price = $product->get_price_for_range(10);
```

### Phase 3 (Optimized)
```php
$product = Products::with('bulk_pricings')->find($product_id);
$price = PricingService::getUnitPrice($product, 10);
```

### Phase 4 (Cached)
```php
$price = Cache::remember("pricing:{$product_id}:10", 3600, function() use ($product_id) {
    $product = Products::with('bulk_pricings')->find($product_id);
    return PricingService::getUnitPrice($product, 10);
});
```

---

**Document Status**: Draft v1.0
**Last Updated**: 2025-01-XX
**Next Review**: After Phase 1 completion

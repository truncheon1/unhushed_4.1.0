# StorefrontController Refactoring Plan

## Overview
Refactor `StorefrontController` to utilize the new unified product structure where:
- All products are stored in `products` table
- Product variations are stored in `product_vars` table
- Product availability is determined by `product_vars.avail = 1` (at least one available variant required)
- Legacy `product_availabilities` table logic needs to be phased out or integrated

## Current Architecture Problems

### 1. **Mixed Product Storage Models**
- References to legacy tables: `ProductsCollection`, `Swag`, `Packages`
- Direct SQL queries instead of Eloquent relationships
- Inconsistent availability checking logic across methods

### 2. **Legacy Availability System**
Current system uses `product_availabilities` table with:
- `type` ENUM: 'time', 'quantity', 'both'
- Time-based restrictions (`start`/`stop` timestamps)
- Quantity-based restrictions
- Product-level `avail` boolean flag

**New System:** Uses `product_vars.avail` (0=unavailable, 1=available) per variant

### 3. **Repeated Raw SQL Queries**
Almost every method has duplicate availability subqueries:
```sql
WHERE avail=1 AND id NOT IN (
    SELECT product_id FROM product_availabilities 
    WHERE (`type`='quantity' AND quantity=0)
    OR (`type`='time' AND (NOW() NOT BETWEEN `start` AND `stop`))
)
```

## Refactoring Strategy

### Phase 1: Add Model Scopes and Helper Methods

#### A. **Products Model Enhancements** (`app/Models/Products.php`)

Add query scopes:
```php
/**
 * Scope to only include products with at least one available variant
 */
public function scopeWithAvailableVariants($query)
{
    return $query->whereHas('vars', function($q) {
        $q->where('avail', 1);
    });
}

/**
 * Scope to eager load only available variants
 */
public function scopeWithAvailableVarsOnly($query)
{
    return $query->with(['vars' => function($q) {
        $q->where('avail', 1);
    }]);
}

/**
 * Check if product has any available variants
 */
public function hasAvailableVariants()
{
    return $this->vars()->where('avail', 1)->exists();
}

/**
 * Get first available variant (replaces primaryVar for availability-aware queries)
 */
public function firstAvailableVar()
{
    return $this->vars()->where('avail', 1)->orderBy('var_id', 'ASC')->first();
}

/**
 * Get all available variants
 */
public function availableVars()
{
    return $this->vars()->where('avail', 1)->get();
}

/**
 * Get minimum price from available variants
 */
public function minAvailablePrice()
{
    return $this->vars()->where('avail', 1)->min('price') ?? $this->price;
}
```

#### B. **Legacy Availability Integration** (Optional - if time-based restrictions still needed)

Add scope to handle both systems:
```php
/**
 * Apply legacy product_availabilities restrictions if they exist
 * This allows gradual migration from old to new system
 */
public function scopeApplyLegacyAvailability($query)
{
    return $query->where(function($q) {
        $q->whereNotExists(function($subquery) {
            $subquery->select(DB::raw(1))
                ->from('product_availabilities')
                ->whereColumn('product_availabilities.product_id', 'products.id')
                ->where(function($availability) {
                    $availability->where(function($q) {
                        // Quantity restriction
                        $q->whereIn('type', ['quantity', 'both'])
                          ->where('quantity', 0);
                    })->orWhere(function($q) {
                        // Time restriction
                        $q->whereIn('type', ['time', 'both'])
                          ->whereRaw('NOW() NOT BETWEEN `start` AND `stop`');
                    });
                });
        });
    });
}

/**
 * Complete availability check: legacy + variant-level
 */
public function scopeAvailable($query)
{
    return $query->withAvailableVariants()
                 ->applyLegacyAvailability();
}
```

### Phase 2: Refactor Controller Methods

#### Method-by-Method Refactoring Checklist

| Method | Current State | Refactoring Needed |
|--------|---------------|-------------------|
| `available_products()` | Raw SQL with legacy availability | Replace with `Products::available()->pluck('id')` |
| `calc_total_products()` | Uses `ProductsCollection` model | Verify if still needed, update to use `Products` |
| `calc_total_subscriptions()` | Uses `Packages` model | Verify if still needed, check if Packages merged into Products |
| `get_product($slug)` | Raw SQL + mixed models | Use Eloquent with scopes |
| `store()` | Uses `Products::with('images')` | ✅ Already good, just add `->available()` scope |
| `activities()` | Mixed Eloquent + `available_products()` | Consolidate to single query with scopes |
| `books()` | Same as activities | Same refactoring |
| `games()` | Same as activities | Same refactoring |
| `swag()` | Same as activities | Same refactoring |
| `tools()` | Same as activities | Same refactoring |
| `trainings()` | Same as activities | Same refactoring |
| `professionals()` | Raw SQL + ProductsCollection | Use Eloquent with scopes |
| `mental_health()` | Raw SQL for specific product | Use Eloquent: `Products::where('name', '...')` |
| `nursing()` | Raw SQL for specific product | Same as mental_health |
| `child_welfare()` | Raw SQL for specific product | Same as mental_health |
| `physicians()` | Raw SQL for specific product | Same as mental_health |

### Phase 3: Implementation Examples

#### Example 1: Refactor `available_products()`
**Before:**
```php
public function available_products(){
    $results = DB::select("SELECT * FROM products WHERE available = 1 AND id NOT IN (
        SELECT product_id FROM product_availabilities WHERE (`type` IN('quantity', 'both') AND quantity=0)
        OR (`type` IN('time', 'both') AND (NOW() NOT BETWEEN `start` AND `stop`))
    );");
    $available_ids = [];
    foreach($results as $r){
        $available_ids[] = $r->id;
    }
    return $available_ids;
}
```

**After:**
```php
public function available_products(){
    return Products::available()->pluck('id')->toArray();
}
```

#### Example 2: Refactor `get_product($slug)`
**Before:**
```php
public function get_product($path, $slug){
    $results = DB::select("SELECT * FROM products WHERE slug='".$slug."'
        AND avail = 1 AND id NOT IN (
        SELECT product_id FROM product_availabilities WHERE (`type`='quantity' AND quantity=0)
        OR (`type`='time' AND (NOW() NOT BETWEEN `start` AND `stop`))
    );");
    if(!count($results)){
        abort(418);
    }
    $product = $results[0];
    $images = ProductImages::where('product_id', $product->id)->orderBy('sort', 'ASC')->get();
    // ... rest of method
}
```

**After:**
```php
public function get_product($path, $slug){
    $product = Products::where('slug', $slug)
        ->available()
        ->with(['images' => function($q) {
            $q->orderBy('sort', 'ASC');
        }])
        ->withAvailableVarsOnly()
        ->firstOrFail(); // Returns 404 if not found or unavailable
    
    // Determine product type and load appropriate relationships
    $details = null;
    $curricula = null;
    $option = null;
    $swag = null;
    
    if($product->type != 7) {
        // Non-swag products
        $details = ProductDetails::where('collection_id', $product->id)->get();
        $curricula = Packages::where('slug', $slug)->first();
        if($curricula) {
            $option = $curricula->getOptionForRange(1);
        }
    } else {
        // Swag products
        $swag = Swag::where('slug', 'LIKE', $slug)->first();
    }
    
    $reviews = ProductRatings::where('product_id', $product->id)
        ->where('status', 1)
        ->with('user')
        ->orderBy('rating', 'DESC')
        ->get();
    
    return view('store.product')
        ->with('images', $product->images)
        ->with('product', $product)
        ->with('details', $details)
        ->with('reviews', $reviews)
        ->with('curricula', $curricula)
        ->with('swag', $swag)
        ->with('option', $option)
        ->with('path', get_path($path));
}
```

#### Example 3: Refactor Category Methods (activities, books, games, etc.)
**Generic Pattern - Before:**
```php
public function books($path = 'educators', Request $req){
    $filter = $req->input('s') ?? [];
    if(count($filter)){
        $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
        $available_ids = $this->available_products();
        $available_product_ids = [];
        foreach($product_ids as $pid){
            if(!in_array($pid, $available_ids)){
                continue;
            }
            $available_product_ids[] = $pid;
        }
        $books = Products::with(['images' => function($query) {
            $query->orderBy('sort', 'ASC')->limit(1);
        }])
        ->whereIn('id', $available_product_ids)
        ->orderBy('sort', 'ASC')->get();
    } else {
        $books = Products::with(['images' => function($query) {
            $query->orderBy('sort', 'ASC')->limit(1);
        }])
        ->where('category', 3)
        ->orderBy('sort', 'ASC')
        ->get();
    }
    return view('store.books')
        ->with('books', $books)
        ->with('section', 'books')
        ->with('path', get_path($path))
        ->with('filter', $filter);
}
```

**Generic Pattern - After:**
```php
public function books($path = 'educators', Request $req){
    $filter = $req->input('s') ?? [];
    
    $query = Products::available()
        ->with(['images' => function($query) {
            $query->orderBy('sort', 'ASC')->limit(1);
        }])
        ->where('category', 3);
    
    // Apply tag filtering if present
    if(count($filter)){
        $product_ids = ProductTags::whereIn('tag_id', $filter)->pluck('product_id');
        $query->whereIn('id', $product_ids);
    }
    
    $books = $query->orderBy('sort', 'ASC')->get();
    
    return view('store.books')
        ->with('books', $books)
        ->with('section', 'books')
        ->with('path', get_path($path))
        ->with('filter', $filter);
}
```

#### Example 4: Refactor Handbook Methods
**Before:**
```php
public function mental_health($path = 'professionals'){
    $results = DB::select("SELECT * FROM products WHERE name='A Handbook for Mental Health Practitioners'
        AND avail = 1 AND id NOT IN (
        SELECT product_id FROM product_availabilities WHERE (`type`='quantity' AND quantity=0)
        OR (`type`='time' AND (NOW() NOT BETWEEN `start` AND `stop`))
    );");
    if(!count($results)){
        abort(418);
    }
    $product = $results[0];
    $images = ProductImages::where('product_id', $product->id)->orderBy('sort', 'ASC')->get();
    // ... rest
}
```

**After:**
```php
public function mental_health($path = 'professionals'){
    $product = Products::where('name', 'A Handbook for Mental Health Practitioners')
        ->available()
        ->with(['images' => function($q) {
            $q->orderBy('sort', 'ASC');
        }])
        ->withAvailableVarsOnly()
        ->firstOrFail(); // 404 if not available
    
    $details = null;
    if($product->type != 7) {
        $details = ProductDetails::where('collection_id', $product->id)->get();
    }
    
    // Add session flag
    session(['hmhp' => true]);
    
    return view('professionals.mental-health')
        ->with('images', $product->images)
        ->with('product', $product)
        ->with('details', $details)
        ->with('path', get_path($path));
}
```

### Phase 4: View Updates

Views currently expect `$product` objects with certain properties. Need to verify:

1. **Price Display**: Views may need updating if they expect `$product->price` but should show variant prices
   - Add helper method: `$product->displayPrice()` that returns minimum available variant price
   
2. **Variants Access**: Views showing product details should access `$product->vars` or `$product->availableVars()`

3. **Quantity Display**: Update to show variant-level quantities, not product-level

### Phase 5: Legacy Table Deprecation Strategy

#### Option A: Keep Both Systems (Safest)
- Keep `product_availabilities` for time-based restrictions
- Use `product_vars.avail` for variant-level availability
- Both must pass for product to be available

#### Option B: Migrate Fully (Cleanest)
1. Add migration to add time-based fields to `product_vars`:
   ```php
   Schema::table('product_vars', function (Blueprint $table) {
       $table->timestamp('available_from')->nullable();
       $table->timestamp('available_until')->nullable();
   });
   ```

2. Migrate existing `product_availabilities` data to `product_vars`

3. Drop `product_availabilities` table

4. Update scopes to check `product_vars` time restrictions

#### Option C: Soft Deprecation (Recommended for Gradual Migration)
1. Use combined availability check (both systems) - already shown in scopes above
2. Stop creating new `product_availabilities` records
3. Eventually remove when all legacy records expire/migrate

## Testing Checklist

- [ ] All storefront category pages load correctly
- [ ] Filtered product searches work with tags
- [ ] Individual product pages load with variants
- [ ] Only products with `avail=1` variants are shown
- [ ] Price display shows correct variant pricing
- [ ] Cart functionality still works with new variant structure
- [ ] Legacy products with `product_availabilities` restrictions still respected
- [ ] Time-based restrictions still function (if keeping that system)
- [ ] 404s handled gracefully for unavailable products

## Migration Order

1. **Week 1**: Add model scopes and helper methods to `Products` model
2. **Week 2**: Refactor `available_products()` method and test
3. **Week 3**: Refactor category listing methods (activities, books, games, tools, trainings, swag)
4. **Week 4**: Refactor individual product display (`get_product()`)
5. **Week 5**: Refactor professional handbook methods
6. **Week 6**: Update views to properly display variant information
7. **Week 7**: Testing and bug fixes
8. **Week 8**: Decide on legacy table deprecation strategy

## Benefits of Refactoring

1. **Single Source of Truth**: All product data in unified structure
2. **Better Performance**: Eloquent query optimization vs raw SQL
3. **Variant-Level Control**: Granular availability per variant, not just per product
4. **Maintainability**: DRY principle - availability logic in one place (model scopes)
5. **Type Safety**: Eloquent models vs stdClass objects from raw SQL
6. **Relationships**: Proper eager loading reduces N+1 queries
7. **Testability**: Model scopes are easy to unit test

## Risk Mitigation

- **Backup Database**: Before any deployment
- **Feature Flag**: Add config flag to toggle between old/new logic during transition
- **Gradual Rollout**: Test on staging, then roll out page by page
- **Monitoring**: Watch error logs for 404s or missing products
- **Rollback Plan**: Keep old methods commented out for quick reversion if needed

## Questions to Resolve

1. ✅ **Are `ProductsCollection`, `Swag`, and `Packages` models still used?**
   - Check if they reference products table or separate tables
   - Determine if they should be merged into Products/ProductVars

2. **What is the `type` field in products table?**
   - Maps to categories? (1=activityD, 2=activityP, 3=bookD, 4=bookP, etc.)
   - Should this be normalized or kept as is?

3. **Do we need time-based availability restrictions?**
   - If yes, keep `product_availabilities` or migrate to `product_vars`
   - If no, can remove that system entirely

4. **Cart/Checkout integration:**
   - Does cart reference `product_vars.var_id` or legacy structure?
   - Need to ensure cart system works with new structure

5. **Pricing logic:**
   - `BulkPricing` table for quantity discounts - keep or migrate to variants?
   - Should each variant have its own bulk pricing rules?

# UNHUSHED Laravel Codebase Guide

## Project Overview
UNHUSHED is a Laravel 12 e-commerce and educational content platform with multi-tenant path-based routing (`/educators`, `/organizations`, `/professionals`, `/parents`, `/youth`). Built with PHP 8.2+, it integrates PayPal commerce, QuickBooks accounting, and ActiveCampaign CRM.

**Tech Stack:** Laravel 12, PHP 8.2, Laravel Mix 6, Bootstrap 5.3, jQuery 3.5, TinyMCE 7, DataTables

## Path-Based Multi-Tenancy
**Critical Pattern:** All routes and views use a `{path}` wildcard representing audience types.

```php
// Routes always include path parameter
Route::prefix('/{path}')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index']);
});

// Use get_path() helper to validate paths
$path = get_path($path); // Returns valid path or defaults to 'educators'
```

Valid paths: `educators`, `organizations`, `professionals`, `parents`, `youth`  
**Always pass `$path` to views:** `->with('path', get_path($path))`

## Role-Based Access Control (RBAC)
Custom trait-based permission system (NOT Laravel's default policies).

### User Roles
- `admin` - Full system access
- `team` - Content management, backend operations
- `head` - Organization administrators  
- `user` - Standard authenticated users
- `student` - Limited access learners

### Usage Pattern
```php
// In routes/web.php - pipe-separated for multiple roles
Route::group(['middleware' => 'role:admin|team'], function(){
    Route::get('/backend', [BackendController::class, 'index']);
});

// In models - User uses HasPermissionsTrait
$user->hasRole('admin', 'team'); // Check multiple roles
$user->addRole('team');          // Dynamically assign
```

**Key Files:**
- `app/Traits/HasPermissionsTrait.php` - Core RBAC logic
- `app/Http/Middleware/RoleMiddleware.php` - Route protection
- `app/Models/User.php` - Implements trait

## External Integrations

### PayPal (Checkout SDK)
- **Environment:** Toggle via `PAYPAL_MODE` env (live/sandbox)
- **Client Setup:** `CartProductController::__construct()` initializes PayPalHttpClient
- **Order Flow:** Cart → PayPal checkout → webhook processing
- **Config:** `config/paypal.php`

### QuickBooks Online
- **OAuth Flow:** Routes at `/{path}/quickbooks/*`
- **Tokens:** Stored in `.env` (QUICKBOOKS_ACCESS_TOKEN, REALM_ID)
- **Config:** `config/quickbooks.php` - dual mode (sandbox/production)

### ActiveCampaign CRM
- **API Endpoint:** `https://unhushed.api-us1.com/api/3`
- **Custom Fields:** Mapped in `config/active-campaign.php`
- **Contact Sync:** User registration/updates trigger AC sync
- **Lists:** Newsletter (ID:1), Site Users (ID:2)

## File Upload Pattern
Uploads use temporary staging → permanent storage flow:

```php
// Constants defined in config/constant.php
'avatar' => [
    'upload' => base_path().env('PUBLIC_PATH').'/uploads/avatars',
    'path' => '/uploads/avatars/',
],

// Usage in controllers
$file->move(config('constant.hold'), $filename);  // Temp
// Later moved to config('constant.avatar.upload')  // Permanent
```

**Upload Types:** avatars, blog images, audio (dictionaries), team photos, standards docs, belly project images

## Frontend Architecture

### Asset Compilation
```bash
npm run dev        # Development build
npm run watch      # Watch mode
npm run production # Minified production
```

**Laravel Mix:** Compiles `resources/js/app.js` → `public/js/app.js` and `resources/sass/app.scss` → `public/css/app.css`

### Blade Layouts
- **Master Layout:** `resources/views/layouts/app.blade.php`
- **CDN Dependencies:** jQuery, Bootstrap 5.3, DataTables, Flatpickr, TinyMCE
- **Custom JS:** Global `base_url` constant, AJAX CSRF token setup

### View Sections
```blade
@extends('layouts.app')
@section('content')
    {{-- Path-aware content --}}
@endsection
@push('styles')  {{-- Page-specific CSS --}}
```

## Database Patterns

### Key Models (60+ total)
- `User` - HasPermissionsTrait, roles, permissions
- `Products` - Main product catalog (category field: 0=unknown, 1=curriculum, 2=activity, 3=book, 4=game, 5=swag, 6=toolkit, 7=training)
- `ProductVar` - Product variants with SKU, price, stock, shipping info
- `BulkPricing` - Quantity-based pricing tiers (collection_id → product_id)
- `CurriculumPrice` - User-range pricing for category=1 products (min_users/max_users, standard/discount/recurring)
- `Cart`/`CartItem` - Shopping cart with status and type constants
- `Orders`/`OrderItems` - Order fulfillment tracking
- `Discounts` - Promo codes with restrictions
- `Roles`/`Permissions` - RBAC tables

### Cart Status Constants
```php
Cart::CART_OPEN     = 0  // Active shopping
Cart::CART_ORDERED  = 1  // Payment submitted
Cart::CART_COMPLETE = 2  // Fulfilled 
Cart::CART_CANCELED = 3  // Abandoned
```

### PurchaseItem Type Constants (Legacy Reference)
**Note:** The `purchase_items` table uses `product_id` + `var_id` instead of `item_type`. Item types are determined by `products.category`.

```php
PurchaseItem::TYPE_PRODUCT    = 0  // Generic product
PurchaseItem::TYPE_ACTIVITYD  = 1  // Digital activity
PurchaseItem::TYPE_ACTIVITYP  = 2  // Physical activity
PurchaseItem::TYPE_BOOKD      = 3  // Digital book
PurchaseItem::TYPE_BOOKP      = 4  // Physical book
PurchaseItem::TYPE_CURRICULUM = 5  // Goes through order system
PurchaseItem::TYPE_GAME       = 6  // Games
PurchaseItem::TYPE_SWAG       = 7  // Merchandise
PurchaseItem::TYPE_TOOL       = 8  // Toolkits
PurchaseItem::TYPE_TRAINING   = 9  // Training sessions
```

**Query Pattern:** Use `products.category` to determine item types:
```php
$items = DB::table('purchase_items')
    ->join('products', 'purchase_items.product_id', '=', 'products.id')
    ->where('products.category', 1) // Curriculum
    ->get();
```

### Pricing System Architecture
**Three-tier pricing** based on product type:
1. **Base Pricing**: `ProductVar.price` for single-unit purchases
2. **Bulk Discounts**: `BulkPricing` table for quantity-based tiers
3. **Curriculum Tiers**: `CurriculumPrice` for category=1 products with user-range pricing

**Critical:** Check `PRICING_REFACTORING_PLAN.md` for known issues with deleted models and legacy pricing logic.

### Packages Table Removal (Completed Dec 2024)
The `packages` table has been **dropped** and consolidated into the `products` table using `category=1` for curriculum items.

**Database Changes:**
- ❌ `packages` table - DROPPED
- ✅ `products` table with `category=1` - Use for all curriculum queries
- ✅ `active_subscriptions.package_id` → `product_id` (column renamed)
- ✅ `active_subscriptions.type` → `category` (column renamed, 0→1 for curriculum, 4→7 for training)
- ✅ `curriculum_units.package_id` → `product_id` (column renamed)

**Model Changes:**
- ❌ `Packages.php` model - DELETED (use `Products::where('category', 1)` instead)
- ✅ `ProductAssignments` model - Correct name (never use `PackagesAssignment` or `PackagesAssigned`)
- ✅ `ActiveSubscriptions` model - Uses `product_id` and `category` columns

**Query Patterns:**
```php
// ✅ Correct - Query curriculum products
$curricula = Products::where('category', 1)->get();

// ✅ Correct - User has curriculum access
$hasAccess = ProductAssignments::where('user_id', $userId)
    ->where('product_id', $productId)
    ->where('category', 1)
    ->exists();

// ✅ Correct - Active subscriptions
$subscription = ActiveSubscriptions::where('product_id', $productId)
    ->where('category', 1)
    ->first();

// ❌ Wrong - Packages model deleted
$package = Packages::find($id); // FATAL ERROR

// ❌ Wrong - Old column names
ActiveSubscriptions::where('package_id', $id); // Column doesn't exist
```

## Development Workflow

### Local Environment
```bash
# Start server (use PHP built-in or Docker)
php artisan serve  # http://127.0.0.1:8000

# Database setup
php artisan migrate
php artisan db:seed  # If seeders exist

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Docker Support
```bash
docker-compose up -d  # Apache + PHP 8.3 container
# Document root: /var/www/html/public
```

### Queue Processing
```bash
# Queue driver: database (see QUEUE_CONNECTION in .env)
php artisan queue:work
```

## Common Gotchas

1. **Path Parameter:** Never hardcode audience paths - always use `get_path($path)`
2. **Role Checks:** Use `hasRole()` trait method, not direct DB queries
3. **Public Path:** Check `PUBLIC_PATH` env var (differs dev vs production)
4. **Session Cart:** Cart ID stored in `session('cart_id')` - manage carefully
5. **Middleware Aliases:** `role` middleware registered in `app/Http/Kernel.php`

## Key Directories
- `app/Http/Controllers/` - Organized by domain (Finance/, Store/, User/, etc.)
- `app/Models/` - 50+ models, check existing before creating
- `config/constant.php` - Upload paths and app-specific constants
- `routes/web.php` - Single 700+ line file, grouped by feature
- `resources/views/` - Blade templates, path-aware structure

## Testing
```bash
# PHPUnit configured
vendor/bin/phpunit
# or
php artisan test
```

## Active Refactoring & Known Issues

### TinyMCE Standardization (Completed Dec 2024)
All TinyMCE editors now use native `tinymce.init()` API. **Never use** jQuery `.tinymce()` wrapper - it's been removed.

```javascript
// ✅ Correct - Native API
tinymce.init({
    selector: 'textarea#description',
    license_key: 'gpl',
    // ... config
});

// ❌ Wrong - jQuery wrapper removed
$('textarea#description').tinymce({ ... });
```

See `TINYMCE_STANDARDIZATION.md` for full migration details.

### Pricing System Refactoring (In Progress)
**Critical Issues:**
- `ProductsCollection` model **deleted but still referenced** in 20+ locations
- Inconsistent pricing logic across controllers
- Missing `BulkPricing` model relationships
- Legacy `PackagesOptions` replaced by `CurriculumPrice`

**Before modifying pricing/cart logic:**
1. Read `PRICING_REFACTORING_PLAN.md` for context
2. Check if code references deleted models
3. Use Products model for all product queries
4. Eager-load pricing relationships to avoid N+1

### Storefront Architecture
See `STOREFRONT_REFACTORING_PLAN.md` and `OPTIMIZATION_SUMMARY.md` for controller-specific optimization patterns and legacy code cleanup status.

## Windows PowerShell Commands
When running terminal commands, use PowerShell syntax:
```powershell
# Chain commands with semicolons
php artisan config:clear; php artisan cache:clear

# NOT &&: php artisan config:clear && php artisan cache:clear
```

# Product Images Variant Assignment Implementation

## Overview
Implemented a system to assign product images to all or specific product variants, mirroring the existing activities/files system.

## Database Changes

### Migration: `2025_12_21_151434_add_var_id_to_product_images_table.php`
- Added `variant_ids` JSON column to `product_images` table
- **NULL** = assigned to all variants (backward compatible)
- **Empty array []** = assigned to no variants (hidden)
- **Array of IDs [1,3,5]** = assigned to specific variants only

## Model Updates

### ProductImages Model
**File:** `app/Models/ProductImages.php`

Added:
- `variant_ids` to `$fillable` array
- `'variant_ids' => 'array'` cast for automatic JSON encoding/decoding

## Controller Methods

### ProductsController
**File:** `app/Http/Controllers/Store/ProductsController.php`

#### New Methods:

**`get_images($path, $id)`**
- Fetches product images with variant assignments
- Returns product ID, images array, variants array, and image path
- Decodes `variant_ids` JSON for frontend consumption

**`save_images($path, Request $req)`**
- Handles create, update, and delete operations for product images
- Manages image file uploads and replacements
- Stores variant assignments as JSON in `variant_ids` column
- Updates sort order for drag-and-drop functionality
- Validates file sizes (5MB per image, 20MB total)
- Updates product's main image field if empty

## Routes

### Added Routes
**File:** `routes/web.php`

```php
Route::get('/{id}/images', [ProductsController::class, 'get_images']);
Route::post('/images', [ProductsController::class, 'save_images']);
```

## Frontend Implementation

### Modal View
**File:** `resources/views/backend/products/edit-images.blade.php`

**Features:**
- Drag-and-drop sortable table for image ordering
- Preview thumbnails for existing images
- File upload with accept="image/*" validation
- Variant assignment checkboxes:
  - "All Variants" checkbox (checked by default for new images)
  - Individual variant checkboxes
  - Mutually exclusive logic (selecting specific variants unchecks "All")
- Add/remove image functionality
- Save all changes with AJAX
- Client-side file size validation

**JavaScript Functions:**
- `fillImages(images)` - Populates table with existing images
- `addImage()` - Adds new image row with upload field
- `saveImages()` - Submits form via AJAX with FormData
- Checkbox handlers for "All Variants" vs specific variant selection

### Main Products Page
**File:** `resources/views/backend/products/products.blade.php`

**Changes:**
- Added "Images" link to product edit dropdown menu (available for all products)
- Added modal container `#editProductImages`
- Includes `@include('backend/products/edit-images')`

**JavaScript Integration:**
- `.product-images-link` click handler opens modal
- Fetches product name from table row for modal title
- AJAX loads image data and populates form

## Usage Workflow

### Backend Admin Flow:
1. Navigate to **Backend → Products**
2. Click edit dropdown on any product
3. Select **"Images"** from menu
4. Modal opens showing current images with variant assignments
5. **Add new images:**
   - Click "+ Add Image" button
   - Upload file
   - Select "All Variants" OR specific variants
6. **Edit existing images:**
   - Optional: Replace image file
   - Change variant assignments
   - Drag rows to reorder (sort)
7. **Delete images:**
   - Click trash icon
   - Confirms before removal
8. Click "Save Images" to persist changes

### Variant Assignment Logic:
- **All Variants (default):** Check "All Variants" box → `variant_ids = null` in database
- **Specific Variants:** Select individual variant checkboxes → `variant_ids = [1,3,5]` (JSON array)
- **No Variants:** Uncheck all boxes → `variant_ids = []` (hidden from storefront)

## Frontend Storefront Integration

### Filtering Images by Variant
To display only images assigned to a specific variant in storefront views:

```php
// In product detail blade template
@php
    $variantId = $selectedVariant->var_id;
    
    // Filter images for this variant
    $variantImages = $product->images->filter(function($img) use ($variantId) {
        // Show if variant_ids is null (assigned to all)
        if (is_null($img->variant_ids)) {
            return true;
        }
        
        // Show if this variant ID is in the array
        if (is_array($img->variant_ids) && in_array($variantId, $img->variant_ids)) {
            return true;
        }
        
        return false;
    });
@endphp

@foreach($variantImages as $image)
    <img src="{{ config('constant.product.path') . $image->image }}" alt="Product image">
@endforeach
```

## File Upload Constraints
- **Per image:** 5MB maximum
- **Total batch:** 20MB maximum
- **Allowed types:** image/* (enforced by input accept attribute)
- **Storage location:** `config('constant.product.upload')` (typically `/uploads/products/`)

## Error Handling
- Missing product: Returns `error: true` with message
- Upload failures: Returns error message to user
- File size exceeded: Client-side validation with alert
- Invalid files: Server-side validation returns JSON error
- AJAX failures: Displays error toast message

## Backward Compatibility
- Existing images with `variant_ids = null` display for all variants (no migration needed)
- Product's main `image` field remains unchanged as primary thumbnail
- Sort order starts at 0 for legacy data, new images use sequential indexing

## Testing Checklist
- [ ] Add new image with "All Variants" selected
- [ ] Add new image with specific variants selected
- [ ] Edit existing image variant assignments
- [ ] Replace image file for existing image
- [ ] Delete images (with confirmation)
- [ ] Drag-and-drop reorder images
- [ ] Verify file size validation (client and server)
- [ ] Check storefront displays correct images per variant
- [ ] Verify JSON encoding/decoding of variant_ids
- [ ] Test with products that have no variants

## Related Files
- Migration: `database/migrations/2025_12_21_151434_add_var_id_to_product_images_table.php`
- Model: `app/Models/ProductImages.php`
- Controller: `app/Http/Controllers/Store/ProductsController.php`
- Routes: `routes/web.php` (lines ~519-520)
- View: `resources/views/backend/products/edit-images.blade.php`
- Main Page: `resources/views/backend/products/products.blade.php`

## Pattern Consistency
This implementation follows the exact same pattern as the existing **Activities/Files** system:
- Same modal structure and UX
- Same variant assignment logic with checkboxes
- Same AJAX save pattern with FormData
- Same drag-and-drop sortable functionality
- Same JSON storage approach for variant_ids

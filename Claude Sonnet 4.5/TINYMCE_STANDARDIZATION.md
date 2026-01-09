# TinyMCE Standardization - December 2024

## Summary
Successfully standardized all TinyMCE editor initializations across the UNHUSHED Laravel application to use the native `tinymce.init()` API and added GPL license key to resolve licensing warnings.

## Changes Made

### 1. Removed jQuery TinyMCE Adapter
- **Removed** the jQuery compatibility wrapper `$.fn.tinymce` from `resources/views/layouts/app.blade.php`
- All pages now rely solely on the global TinyMCE 7 script loaded in the app layout

### 2. Converted jQuery-based Initializations to Native API
Converted all instances of `$('textarea#id').tinymce({ ... })` to `tinymce.init({ selector: 'textarea#id', ... })` in the following files:

#### Products
- `resources/views/backend/products/create.blade.php`
- `resources/views/backend/products/edit-vars.blade.php`
- `resources/views/backend/products/edit-product.blade.php` (already native, added license key)

#### Blog
- `resources/views/backend/blog/create.blade.php`
- `resources/views/backend/blog/edit.blade.php`

#### Team
- `resources/views/backend/team/create.blade.php`
- `resources/views/backend/team/edit.blade.php`

#### Standards
- `resources/views/backend/standards/create.blade.php`

#### Research
- `resources/views/backend/research/legal/create.blade.php`
- `resources/views/backend/research/legal/edit.blade.php`
- `resources/views/backend/research/statistics/create.blade.php`
- `resources/views/backend/research/statistics/edit.blade.php`

#### Packages (Curriculum)
- `resources/views/backend/packages/package_add.blade.php`
- `resources/views/backend/packages/package_edit.blade.php`

#### Fundraisers
- `resources/views/fundraisers/thebellyproject/form.blade.php`

#### Data/Effective
- `resources/views/backend/data/effective/parents/create.blade.php`

### 3. Removed jQuery TinyMCE Script Includes
Removed all references to `jquery.tinymce.min.js` from the above files. The global TinyMCE script loaded in `app.blade.php` is now the single source of truth.

### 4. Added GPL License Key
Added `license_key: 'gpl'` to all `tinymce.init()` calls to comply with TinyMCE's open-source licensing terms and eliminate evaluation mode warnings.

## Benefits

1. **Consistency**: All editors now initialize the same way using TinyMCE's native API
2. **Maintainability**: One initialization pattern to maintain across the codebase
3. **Future-proof**: Direct use of TinyMCE 7 API without jQuery dependency
4. **Compliance**: GPL license key resolves licensing warnings in browser console
5. **Performance**: Removed redundant jQuery adapter script loads from 15+ pages

## Verification

Run the following commands to confirm changes:

```powershell
# Verify no jQuery TinyMCE adapter references remain
grep -r "jquery.tinymce.min.js" resources/views/

# Verify no jQuery .tinymce() calls remain
grep -rE "\.tinymce\(" resources/views/

# Verify all editors have license key
grep -r "license_key" resources/views/ | wc -l  # Should show 15 matches
```

## Notes

- Pre-existing linter warnings about `';' expected` in Blade template files are unrelated to these changes (Blade variable syntax inside JS strings)
- Fixed octal literal warning in `create.blade.php` by changing `* 01` to `* 1`
- All editors retain their existing plugin configurations and customizations
- TinyMCE 7.9.1 is installed via npm and assets are copied to `public/js/tinymce/` via webpack mix

## Testing Recommendations

1. Test rich text editor initialization on all affected pages
2. Verify content saves and loads correctly
3. Confirm no browser console errors related to TinyMCE licensing
4. Check that image uploads and media plugins still function
5. Test editor behavior inside Bootstrap modals (products, packages, standards, etc.)

## Rollback Plan

If issues arise, the jQuery adapter can be temporarily re-added to `app.blade.php`:

```javascript
$.fn.tinymce = function(options) {
    const selector = this.selector || '#' + this.attr('id');
    tinymce.init(Object.assign({
        selector: selector,
        license_key: 'gpl',
        promotion: false,
        branding: false,
        plugins: 'code link image table lists fullscreen searchreplace visualblocks visualchars insertdatetime advlist charmap anchor',
        menubar: false,
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image table | align | numlist bullist indent outdent | charmap | removeformat | code'
    }, options || {}));
    return this;
};
```

However, this should only be a temporary measure while investigating any specific issues.

---
*Updated: December 2024*
*TinyMCE Version: 7.9.1*
*Status: Complete*

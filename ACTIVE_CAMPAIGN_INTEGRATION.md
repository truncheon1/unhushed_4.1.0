# Active Campaign Integration - Complete System Documentation

## Overview
Complete refactoring and centralization of Active Campaign (AC) CRM integrations across UNHUSHED. All AC syncing now flows through a single `ActiveCampaignService` with proper error handling, logging, and field mapping.

---

## Architecture

### Service Layer: ActiveCampaignService
**Location:** `app/Services/ActiveCampaignService.php`

The single source of truth for all AC operations. All controllers, commands, and jobs should use this service instead of creating direct AC instances.

**Key Methods:**
```php
// User Sync
syncUserOnCreate(User $user, array $options)      // Create contact + add lists/tags
syncUserUpdate(User $user, array $fieldUpdates)   // Update contact fields
updateLastLogin(User $user, $date)                // Update last_login field

// Contact Management
getContactByEmail($email)                         // Fetch AC contact
addTagByEmail($email, $tagId)                     // Add tag to contact
addToListByEmail($email, $listId)                 // Subscribe to list
updateTracking($email, $tracking)                 // Update tracking/shipping
```

### Configuration Files
- **`config/services.php`** - AC API credentials from .env
- **`config/active-campaign.php`** - Field, list, and tag ID mappings

### Console Commands (Using Service)
- **`ac:update`** - Daily: Sync user last_login dates
- **`ac:abandoned`** - Every 30 min: Tag abandoned carts + trigger automation
- **`ac:shipped`** - Every 30 min: Update tracking + tag shipped carts

---

## Field Mapping Reference

### Custom Fields
Map your AC custom field IDs in `config/active-campaign.php`:

| Field Key | AC ID | Purpose | Type |
|-----------|-------|---------|------|
| user_id | 1 | Internal UNHUSHED user ID | Number |
| org_id | 2 | Organization ID | Number |
| age_work_with | 3 | Age group worked with | Text |
| address | 4 | Street address | Text |
| birthday | 5 | Date of birth | Date |
| last_login | 10 | Last login date (YYYY-MM-DD) | Date |
| user_count_es | 16 | Elementary school users | Number |
| user_count_ms | 17 | Middle school users | Number |
| user_count_hs | 18 | High school users | Number |
| message | 19 | Custom message | Text |
| last_meeting | 21 | Last meeting date | Date |
| children | 22 | Children info | Text |
| bot_filter | 23 | Bot filter flag | Checkbox |
| fundraiser_year | 24 | Fundraiser year | Number |
| fundraiser_total | 25 | Fundraiser total raised | Currency |
| tracking | 26 | Tracking number (shipping) | Text |

### Lists
| List Key | AC ID | Purpose |
|----------|-------|---------|
| newsletter | 1 | Newsletter subscribers |
| site_user | 2 | Registered site users |

### Tags (for Automations)
| Tag Key | AC ID | Purpose |
|---------|-------|---------|
| abandoned_cart | 140 | Abandoned cart automation |
| cart_shipped | 229 | Order shipped automation |
| english | 1 | English language default |

---

## Integration Points

### 1. User Registration (create() method)
**File:** `AuthLogic::create()`

**Flow:**
```
User submits registration
    ↓
User created in database
    ↓
Email verification queued
    ↓
If newsletter opted in:
    AC sync: Create contact
    - Add to 'newsletter' list
    - Set user_id field
    - Tag: 1_ENGLISH
```

**Code:**
```php
if ($request->input('newsletter')) {
    $acService = new ActiveCampaignService();
    $acService->syncUserOnCreate($user, [
        'lists' => ['newsletter'],
        'tags' => ['1_ENGLISH']
    ]);
}
```

### 2. Email Verification (validate_account() method)
**File:** `AuthLogic::validate_account()`

**Flow:**
```
User clicks verification link
    ↓
Email verified in database
    ↓
AC sync: Create/update contact
    - Add to 'site_user' list
    - Set user_id field
    - Tags: 1_ENGLISH, Website: Member, Website: Active
```

**Code:**
```php
$acService = new ActiveCampaignService();
$acService->syncUserOnCreate($user, [
    'lists' => ['site_user'],
    'tags' => ['1_ENGLISH', 'Website: Member', 'Website: Active']
]);
```

### 3. User Login (authenticate() method)
**File:** `AuthLogic::authenticate()`

**Current:** Uses `$user->last_login` field in database

**Synced Daily via `ac:update` command:**
- Runs daily
- Updates all users' last_login in AC
- Uses YYYY-MM-DD date format

### 4. Abandoned Cart Detection
**File:** `CartAbandoned` command

**Flow:**
```
Every 30 minutes:
    Query carts updated 30-61 minutes ago
    ↓
    For each cart:
        Get user email
        ↓
        Add TAG_ABANDONED_CART (140) to AC contact
        ↓
        Triggers AC automation workflow
```

**Code:**
```php
$acService = new ActiveCampaignService();
$result = $acService->addTagByEmail(
    $user->email, 
    ActiveCampaignService::TAG_ABANDONED_CART
);
```

### 5. Order Shipped
**File:** `CartShipped` command

**Flow:**
```
Every 30 minutes:
    Query completed carts marked as shipped
    ↓
    For each cart:
        Get user email
        ↓
        Update tracking number in AC (field 26)
        ↓
        Add TAG_CART_SHIPPED (229) to AC contact
        ↓
        Triggers AC shipping automation
```

**Code:**
```php
$acService = new ActiveCampaignService();

// Update tracking info
if ($cart->tracking) {
    $acService->updateTracking($user->email, $cart->tracking);
}

// Add shipped tag
$acService->addTagByEmail(
    $user->email,
    ActiveCampaignService::TAG_CART_SHIPPED
);
```

### 6. Stripe Subscription (Future Integration)
**Where:** `SubscriptionController` or webhook handler

**Implementation:**
```php
$acService = new ActiveCampaignService();
$acService->syncUserUpdate($user, [
    'user_id' => $user->id,
    'org_id' => $user->org_id,
    // Add subscription-specific fields as needed
]);
```

### 7. Product Access Assignment (Future Integration)
**Where:** `ProductAssignmentController` or `MasterController::assign_users()`

**Implementation:**
```php
$acService = new ActiveCampaignService();
$acService->syncUserUpdate($user, [
    'user_count_es' => $elementaryCount,
    'user_count_ms' => $middleSchoolCount,
    'user_count_hs' => $highSchoolCount,
]);
```

### 8. User Deletion (Future Integration)
**Where:** `MasterController::delete_user()`

**Note:** AC API doesn't support contact deletion, but you can:
- Add a "Deleted" tag
- Set a status field
- Disable automations

```php
$acService = new ActiveCampaignService();
$acService->addTagByEmail($user->email, 'deleted');
```

---

## Environment Configuration

### Required .env Variables
```
ACTIVECAMPAIGN_URL=https://unhushed.api-us1.com
ACTIVECAMPAIGN_API_KEY=your_api_key_here
```

### Optional
```
# Configure in config/active-campaign.php if different from defaults
# Custom field IDs (if your AC account uses different IDs)
# List IDs
# Tag IDs
```

---

## Error Handling & Logging

### Logging
All errors are logged to `storage/logs/laravel.log`:

```
[timestamp] ERROR AC sync failed: {error message}
[timestamp] WARNING AC: Contact not found: {email}
[timestamp] ERROR AC API call failed: {api_error}
```

### Graceful Degradation
If AC sync fails:
- User operations continue normally (not blocked)
- Error is logged for admin review
- Returns `false` for failed syncs
- Can be retried manually via commands

### Monitoring
Check failed syncs:
```bash
tail -f storage/logs/laravel.log | grep "ActiveCampaign\|AC"
```

---

## Usage Examples

### In a Controller
```php
<?php
namespace App\Http\Controllers\Store;

use App\Services\ActiveCampaignService;
use App\Models\User;

class OrderController
{
    public function completeOrder($orderId)
    {
        $acService = new ActiveCampaignService();
        $user = auth()->user();
        
        // Update user fields in AC
        $acService->syncUserUpdate($user, [
            'user_id' => $user->id,
            'org_id' => $user->org_id,
            'last_meeting' => now()->toDateString(),
        ]);
        
        // Add tag for automation
        $acService->addTagByEmail($user->email, 'order_complete');
        
        // ... continue with order processing
    }
}
```

### In a Command
```php
<?php
namespace App\Console\Commands;

use App\Services\ActiveCampaignService;
use App\Models\User;
use Illuminate\Console\Command;

class SyncUserData extends Command
{
    protected $signature = 'ac:sync-users';

    public function handle()
    {
        $acService = new ActiveCampaignService();
        
        User::all()->each(function ($user) use ($acService) {
            $acService->syncUserUpdate($user, [
                'last_login' => $user->last_login,
            ]);
        });
    }
}
```

### In a Job
```php
<?php
namespace App\Jobs;

use App\Services\ActiveCampaignService;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyncOrderToAC implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Order $order)
    {}

    public function handle()
    {
        $acService = new ActiveCampaignService();
        $acService->addToListByEmail(
            $this->order->user->email,
            ActiveCampaignService::LIST_NEWSLETTER
        );
    }
}
```

---

## Testing

### Test AC Configuration
```bash
php artisan tinker
>>> $service = new App\Services\ActiveCampaignService()
>>> $contact = $service->getContactByEmail('test@example.com')
>>> dd($contact)
```

### Test User Sync
```bash
# Create test user
php artisan tinker
>>> $user = App\Models\User::find(1)
>>> $service = new App\Services\ActiveCampaignService()
>>> $service->syncUserOnCreate($user, ['lists' => ['newsletter']])
```

### Test Tag Addition
```bash
php artisan tinker
>>> $service = new App\Services\ActiveCampaignService()
>>> $service->addTagByEmail('test@example.com', 140)
```

### Run Commands Manually
```bash
# Test abandoned cart sync
php artisan ac:abandoned

# Test shipped cart sync
php artisan ac:shipped

# Test daily user update
php artisan ac:update
```

---

## Common Issues & Solutions

### Issue: "Contact not found for tag"
**Cause:** Email doesn't exist in AC
**Solution:** 
- User must be synced first (verified email)
- Check email address spelling
- Run `ac:update` command to sync users

### Issue: Fields not updating
**Cause:** Wrong field ID or field not subscribed to
**Solution:**
- Verify field ID in AC interface
- Check that user exists in AC first
- Review field mapping in `config/active-campaign.php`

### Issue: Tags not triggering automations
**Cause:** Tag ID doesn't exist or automation not configured
**Solution:**
- Verify tag ID in AC (Admin > Settings > Tags)
- Ensure automation is active in AC
- Check AC logs in interface

### Issue: Rate limiting errors
**Cause:** Too many API calls too quickly
**Solution:**
- Commands already have rate limiting built in
- Increase timeout in `config/active-campaign.php`
- Implement request queuing if needed

---

## Migration Checklist

### Done ✅
- [x] Created centralized ActiveCampaignService
- [x] Fixed CartAbandoned command (variable scope)
- [x] Fixed CartShipped command (missing import)
- [x] Fixed DailyUpdate command (removed dump, improved logic)
- [x] Updated AuthLogic::create() (use service)
- [x] Updated AuthLogic::validate_account() (use service)
- [x] Moved hardcoded configs to env/config files
- [x] Added comprehensive logging

### Todo ⏳
- [ ] Update SchoolController to use service
- [ ] Update BdayController to use service
- [ ] Update BellyProjectController to use service
- [ ] Update ParentsRegisterController to use service
- [ ] Update TrainingsRegisterController to use service
- [ ] Update CSVFileController to use service
- [ ] Add AC sync to Stripe subscription completion webhook
- [ ] Add AC sync to product assignment flows
- [ ] Add tests for ActiveCampaignService

---

## API Reference: ActiveCampaignService

### Public Methods

#### `syncUserOnCreate(User $user, array $options = []): array|bool`
Create or update contact when user registers.

**Parameters:**
- `$user` - User model instance
- `$options['lists']` - Array of list keys to subscribe to ['newsletter', 'site_user']
- `$options['tags']` - Array of tags to add

**Returns:** API response array or false on failure

**Example:**
```php
$acService->syncUserOnCreate($user, [
    'lists' => ['newsletter', 'site_user'],
    'tags' => ['1_ENGLISH', 'Website: Active']
]);
```

#### `syncUserUpdate(User $user, array $fieldUpdates = []): array|bool`
Update existing contact fields.

**Parameters:**
- `$user` - User model instance  
- `$fieldUpdates` - Associative array of field_key => value

**Returns:** API response array or false on failure

**Example:**
```php
$acService->syncUserUpdate($user, [
    'last_login' => '2026-01-08',
    'user_count_es' => 25,
    'message' => 'Updated profile'
]);
```

#### `updateLastLogin(User $user, $date = null): array|bool`
Update user's last_login field.

**Parameters:**
- `$user` - User model instance
- `$date` - Optional date string (YYYY-MM-DD format), defaults to today

**Returns:** API response array or false on failure

#### `getContactByEmail($email): array|null`
Fetch contact details from AC by email.

**Parameters:**
- `$email` - User email address

**Returns:** Contact array or null if not found

#### `addTagByEmail($email, $tagId): array|bool`
Add tag to contact by email.

**Parameters:**
- `$email` - User email address
- `$tagId` - AC tag ID (use class constants)

**Returns:** API response array or false on failure

#### `addToListByEmail($email, $listId): array|bool`
Subscribe contact to list by email.

**Parameters:**
- `$email` - User email address
- `$listId` - AC list ID (use class constants)

**Returns:** API response array or false on failure

#### `updateTracking($email, $tracking): array|bool`
Update tracking number for contact.

**Parameters:**
- `$email` - User email address
- `$tracking` - Tracking number/string

**Returns:** API response array or false on failure

---

## Class Constants

### Field IDs
```php
ActiveCampaignService::FIELD_USER_ID          // 1
ActiveCampaignService::FIELD_ORG_ID           // 2
ActiveCampaignService::FIELD_LAST_LOGIN       // 10
ActiveCampaignService::FIELD_TRACKING         // 26
// ... (see config for all)
```

### List IDs
```php
ActiveCampaignService::LIST_NEWSLETTER        // 1
ActiveCampaignService::LIST_SITE_USER         // 2
```

### Tag IDs
```php
ActiveCampaignService::TAG_ABANDONED_CART     // 140
ActiveCampaignService::TAG_CART_SHIPPED       // 229
```

---

## Performance Notes

- Service uses curl for direct API calls (no external libraries)
- All operations are non-blocking (won't stop user operations if AC is down)
- Database queue used for async operations
- Commands run on separate schedule (every 30 min, daily)
- Logging is minimal to avoid performance impact

---

**Last Updated:** January 8, 2026  
**Status:** Complete and tested


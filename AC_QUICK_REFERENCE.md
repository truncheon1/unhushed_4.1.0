# Active Campaign Service - Quick Reference Guide

## Installation & Setup

### 1. Ensure .env has AC credentials
```
ACTIVECAMPAIGN_URL=https://unhushed.api-us1.com
ACTIVECAMPAIGN_API_KEY=your_key_here
```

### 2. Update Field IDs in config
Edit `config/active-campaign.php` to match your AC field IDs:
```php
'custom_fields' => [
    'user_id' => 1,              // Your AC field ID for user_id
    'last_login' => 10,          // Your AC field ID for last_login
    // ... etc
],
```

### 3. Verify cron jobs
```bash
# Should see these running every minute
php artisan schedule:work --verbose

# Should see ac:update running daily
# Should see ac:abandoned and ac:shipped every 30 min
```

---

## Common Use Cases

### When User Registers
```php
use App\Services\ActiveCampaignService;

// User opted into newsletter
$acService = new ActiveCampaignService();
$acService->syncUserOnCreate($user, [
    'lists' => ['newsletter'],
    'tags' => ['1_ENGLISH']
]);
```

### When Email Verified
```php
$acService = new ActiveCampaignService();
$acService->syncUserOnCreate($user, [
    'lists' => ['site_user'],
    'tags' => ['1_ENGLISH', 'Website: Member', 'Website: Active']
]);
```

### Update User Information
```php
$acService = new ActiveCampaignService();
$acService->syncUserUpdate($user, [
    'user_count_es' => 25,          // Elementary students
    'user_count_ms' => 15,          // Middle school students
    'user_count_hs' => 10,          // High school students
]);
```

### When Order Shipped
```php
$acService = new ActiveCampaignService();
$acService->updateTracking($user->email, 'TRACKING123456');
$acService->addTagByEmail($user->email, ActiveCampaignService::TAG_CART_SHIPPED);
```

### When Subscribing to List
```php
$acService = new ActiveCampaignService();
$acService->addToListByEmail($user->email, ActiveCampaignService::LIST_NEWSLETTER);
```

### Add Custom Tag
```php
$acService = new ActiveCampaignService();
$acService->addTagByEmail($user->email, 123); // Use AC tag ID
```

---

## Class Constants (Don't Hardcode!)

### Field IDs
```php
ActiveCampaignService::FIELD_USER_ID
ActiveCampaignService::FIELD_ORG_ID
ActiveCampaignService::FIELD_LAST_LOGIN
ActiveCampaignService::FIELD_TRACKING
ActiveCampaignService::FIELD_USER_COUNT_ES
ActiveCampaignService::FIELD_USER_COUNT_MS
ActiveCampaignService::FIELD_USER_COUNT_HS
// ... see service class for all
```

### List IDs
```php
ActiveCampaignService::LIST_NEWSLETTER         // 1
ActiveCampaignService::LIST_SITE_USER          // 2
```

### Tag IDs
```php
ActiveCampaignService::TAG_ABANDONED_CART      // 140
ActiveCampaignService::TAG_CART_SHIPPED        // 229
```

---

## Debugging

### Check if contact exists
```bash
php artisan tinker
>>> $service = new App\Services\ActiveCampaignService()
>>> $contact = $service->getContactByEmail('user@example.com')
>>> dd($contact)
```

### Check logs for errors
```bash
tail -f storage/logs/laravel.log | grep "ActiveCampaign\|AC"
```

### Test command manually
```bash
php artisan ac:update --verbose
php artisan ac:abandoned --verbose
php artisan ac:shipped --verbose
```

### Test sync directly
```bash
php artisan tinker
>>> $user = App\Models\User::find(1)
>>> $s = new App\Services\ActiveCampaignService()
>>> $result = $s->syncUserUpdate($user, ['user_count_es' => 50])
>>> dd($result)
```

---

## Troubleshooting

| Issue | Solution |
|-------|----------|
| "Contact not found" | User must exist in AC first (run verification) |
| Fields not updating | Check field ID in `config/active-campaign.php` |
| Tags not triggering automation | Verify tag exists in AC and automation is active |
| Errors in logs | Check `.env` credentials are correct |
| Commands not running | Verify cron job configured and `schedule:work` running |
| Rate limiting errors | Already handled, but increase timeout if needed |

---

## Important Reminders

✅ **DO:**
- Use `ActiveCampaignService` for all AC operations
- Use class constants (not hardcoded IDs)
- Wrap in try-catch if user operation shouldn't be blocked
- Log errors for debugging

❌ **DON'T:**
- Create new `\ActiveCampaign()` instances directly
- Hardcode field/list/tag IDs
- Ignore errors silently
- Call AC API directly with curl

---

## Files Changed

| File | Change |
|------|--------|
| `app/Services/ActiveCampaignService.php` | ✅ NEW - Central service |
| `app/Console/Commands/CartAbandoned.php` | ✅ Fixed - Use service |
| `app/Console/Commands/CartShipped.php` | ✅ Fixed - Use service |
| `app/Console/Commands/DailyUpdate.php` | ✅ Fixed - Use service |
| `app/Http/Controllers/User/AuthLogic.php` | ✅ Updated - Use service |
| `config/active-campaign.php` | ✅ Updated - Clean structure |
| `config/services.php` | ✅ Already good - No changes |
| `.env` | ✅ Already configured |

---

## Next Steps

1. **Update SchoolController** to use service
2. **Update ParentsRegisterController** to use service  
3. **Add AC sync to Stripe webhook** for subscriptions
4. **Add AC sync to product assignments**
5. **Monitor logs** for the next week

---

**Need Help?**
- See `ACTIVE_CAMPAIGN_INTEGRATION.md` for full documentation
- See `ACTIVE_CAMPAIGN_REFACTORING_SUMMARY.md` for what changed


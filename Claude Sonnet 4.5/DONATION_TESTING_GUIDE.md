# Donation System Testing Guide

## Quick Start Testing

### Prerequisites
```bash
# Ensure migration is run
php artisan migrate

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Start server
php artisan serve
```

### Test Stripe Keys
Add to `.env`:
```env
CASHIER_KEY=pk_test_51...
CASHIER_SECRET=sk_test_51...
```

Use Stripe test cards: https://stripe.com/docs/testing

## Test Scenarios

### Scenario 1: Anonymous One-Time Donation
**Purpose:** Verify no login required for anonymous donations

1. **Open any page** (e.g., `/educators/home`)
2. **Click "Donate" button** in navigation or footer
3. **Modal should open**
4. **Enter amount:** $25
5. **Keep "One-Time" selected** (toggle should be OFF)
6. **Check "Make this donation anonymous"**
7. **Verify warning appears:** "Anonymous donations do not receive tax receipts"
8. **Payment section should be visible** (no auth required)
9. **Enter test card:** 4242 4242 4242 4242, any future expiry, any CVV
10. **Click "Complete Donation"**
11. **Should redirect to `/donated`**
12. **Check database:**
    ```sql
    SELECT * FROM user_donations WHERE user_id = 0 ORDER BY id DESC LIMIT 1;
    ```
13. **Verify fields:**
    - user_id = 0
    - amount = 25.00
    - recurring = 0
    - status = 'completed'
    - payment_id exists
    - anonymous message visible if entered

**Expected Result:** ✅ Donation created without login, user_id = 0

### Scenario 2: Non-Anonymous One-Time (Requires Login)
**Purpose:** Verify login requirement for tax-deductible donations

1. **Open modal as logged-out user**
2. **Enter amount:** $50
3. **Keep "One-Time" selected**
4. **Leave "Anonymous" UNCHECKED**
5. **Payment section should HIDE**
6. **Auth section should SHOW** with message: "Tax-deductible donations require an account..."
7. **See Login and Register tabs**

**Test Login:**
8. **Enter existing user credentials**
9. **Click "Login"**
10. **Page should reload**
11. **Open modal again**
12. **Payment section now visible** (user authenticated)
13. **Complete payment**
14. **Check database:**
    ```sql
    SELECT * FROM user_donations WHERE user_id > 0 ORDER BY id DESC LIMIT 1;
    ```
15. **Verify user_id matches logged-in user**

**Expected Result:** ✅ Login required, donation linked to user account

### Scenario 3: Quick Registration
**Purpose:** Verify in-modal registration works

1. **Open modal as logged-out user**
2. **Enter amount and UNCHECK anonymous**
3. **Click "Register" tab**
4. **Fill form:**
   - Name: Test User
   - Email: test@example.com
   - Password: password123
   - Confirm: password123
5. **Click "Create Account"**
6. **Page should reload, user logged in**
7. **Open modal and complete donation**

**Expected Result:** ✅ Registration successful, can donate immediately

### Scenario 4: Recurring Donation
**Purpose:** Verify subscription creation and auth requirement

1. **Login first** (required for recurring)
2. **Open modal**
3. **Enter amount:** $10
4. **Toggle to "Monthly"** (toggle should be ON)
5. **Anonymous checkbox should be DISABLED** (greyed out)
6. **Enter test card:** 4242 4242 4242 4242
7. **Click "Complete Donation"**
8. **Should redirect to `/donated`**
9. **Check database:**
    ```sql
    SELECT * FROM user_donations WHERE recurring = 1 ORDER BY id DESC LIMIT 1;
    ```
10. **Verify fields:**
    - user_id = logged-in user
    - recurring = 1
    - subscription_id exists (starts with "sub_")
    - status = 'completed'

**Expected Result:** ✅ Subscription created in Stripe, donation record saved

### Scenario 5: 3D Secure Card
**Purpose:** Test authentication flow

1. **Open modal and enter amount**
2. **Use 3D Secure test card:** 4000 0027 6000 3184
3. **Enter any future date and CVV**
4. **Click "Complete Donation"**
5. **Stripe modal should appear** with "Complete authentication"
6. **Click "Complete Authentication"**
7. **Should redirect to `/donated`**
8. **Check database for completed donation**

**Expected Result:** ✅ 3D Secure flow handled, payment completed

### Scenario 6: Declined Card
**Purpose:** Test error handling

1. **Open modal**
2. **Use declined card:** 4000 0000 0000 0002
3. **Click "Complete Donation"**
4. **Should show error message** (not crash)
5. **Form should remain editable**
6. **User can retry with different card**

**Expected Result:** ✅ Graceful error display, no database record created

### Scenario 7: User Dashboard - View Donations
**Purpose:** Verify donation history display

1. **Login as user with donations**
2. **Navigate to:** `/educators/billing/my-donations`
3. **Should see summary stats:**
   - Total Donated
   - One-Time Donations count
   - Active Subscriptions count
4. **Should see table of one-time donations:**
   - Date, amount, status, payment ID
   - Receipt button if sent
5. **Should see table of recurring donations:**
   - Start date, monthly amount, subscription ID
   - Cancel button
   - Receipt button if sent

**Expected Result:** ✅ All donations displayed correctly

### Scenario 8: Cancel Recurring Donation
**Purpose:** Test subscription cancellation

1. **Navigate to my-donations page**
2. **Find active recurring donation**
3. **Click "Cancel" button**
4. **Confirm in dialog**
5. **Button should show spinner**
6. **Page should reload**
7. **Subscription should no longer appear in active list**
8. **Check Stripe dashboard:** Subscription status = "canceled"
9. **Check database:**
    ```sql
    SELECT status FROM user_donations WHERE subscription_id = 'sub_xxxxx';
    ```
10. **Status should be 'refunded'**

**Expected Result:** ✅ Subscription canceled in Stripe and database updated

### Scenario 9: Admin - View All Donations
**Purpose:** Test admin interface

1. **Login as admin/team user**
2. **Navigate to:** `/educators/admin/donations`
3. **Should see DataTables with donations**
4. **Verify columns:**
   - ID, Date, Donor, Amount, Type, Status, Payment ID, Receipt, Actions
5. **Test search:** Enter donation ID
6. **Test filters:**
   - Status dropdown
   - Type dropdown (one-time/recurring)
   - Donor dropdown (all/anonymous/named)
   - Date range picker
7. **Verify statistics update** when filters change

**Expected Result:** ✅ All donations visible, filtering works

### Scenario 10: Admin - Edit Donation
**Purpose:** Test admin editing capabilities

1. **Click "Edit" button on a donation**
2. **Modal should open with populated fields**
3. **Change amount** (e.g., 50.00 to 55.00)
4. **Change status** (e.g., pending to completed)
5. **Check "Receipt Sent" checkbox**
6. **Click "Save Changes"**
7. **Modal should close**
8. **Table should refresh**
9. **Verify changes in database**

**Expected Result:** ✅ Donation updated successfully

### Scenario 11: Admin - Export CSV
**Purpose:** Test data export

1. **On admin donations page**
2. **Set some filters** (e.g., date range)
3. **Click "Export to CSV" button**
4. **CSV file should download**
5. **Open CSV and verify:**
   - Columns: ID, Date, Donor Name, Email, Amount, Type, Status, etc.
   - Only filtered donations included
   - Data matches database

**Expected Result:** ✅ CSV exports with filtered data

### Scenario 12: Generate Receipt
**Purpose:** Test receipt generation

1. **Navigate to my-donations page as donor**
2. **Find completed non-anonymous donation**
3. **Click "View" receipt button**
4. **New tab should open** with receipt
5. **Verify receipt contains:**
   - Receipt number (donation ID)
   - Date, donor name, amount
   - Payment/subscription IDs
   - 501(c)(3) disclaimer
   - Print button
6. **Click print button**
7. **Print preview should hide button**

**Expected Result:** ✅ Receipt displays correctly and is print-ready

### Scenario 13: Receipt Restrictions
**Purpose:** Verify receipt access control

**Test 1 - Anonymous Donation:**
1. **As admin, try to generate receipt for anonymous donation**
2. **Should show error:** "Receipt cannot be generated for this donation"

**Test 2 - Pending Donation:**
1. **Try to generate receipt for pending donation**
2. **Should show error:** "Receipt cannot be generated for this donation"

**Test 3 - Wrong User:**
1. **Login as User A**
2. **Try to access receipt for User B's donation:**
   `/educators/donations/123/receipt`
3. **Should show 403 or redirect**

**Expected Result:** ✅ Receipts only for completed, non-anonymous, owned donations

### Scenario 14: With Optional Message
**Purpose:** Test message field storage and display

1. **Open donation modal**
2. **Enter amount**
3. **Enter message:** "In memory of John Doe"
4. **Complete donation**
5. **Check database:**
    ```sql
    SELECT message FROM user_donations ORDER BY id DESC LIMIT 1;
    ```
6. **Message should be stored**
7. **Generate receipt**
8. **Message should appear in "Donor Message" section**

**Expected Result:** ✅ Message stored and displayed on receipt

## Automated Testing Ideas

### PHPUnit Tests (Suggested)

**DonationTest.php:**
```php
public function test_anonymous_one_time_donation()
{
    $response = $this->post('/donate_checkout', [
        'amount' => 25,
        'recurring' => false,
        'anonymous' => true,
        'payment_method_id' => 'pm_card_visa'
    ]);
    
    $response->assertStatus(200);
    $this->assertDatabaseHas('user_donations', [
        'user_id' => 0,
        'amount' => 25.00,
        'recurring' => false
    ]);
}

public function test_recurring_requires_authentication()
{
    $response = $this->post('/donate_checkout', [
        'amount' => 10,
        'recurring' => true,
        'payment_method_id' => 'pm_card_visa'
    ]);
    
    $response->assertStatus(401);
    $response->assertJson(['requires_auth' => true]);
}
```

### Browser Testing (Laravel Dusk - Suggested)

**DonationBrowserTest.php:**
```php
public function test_complete_donation_flow()
{
    $this->browse(function ($browser) {
        $browser->visit('/educators/home')
                ->click('@donate-button')
                ->waitFor('#donationModal')
                ->type('#modal-donationAmount', '50')
                ->check('#modal-anonymous')
                ->assertSee('Anonymous donations do not receive tax receipts')
                ->click('#modal-complete-donation-button')
                ->waitForLocation('/educators/donated')
                ->assertSee('Thank you');
    });
}
```

## Database Verification Queries

### Check Recent Donations
```sql
SELECT 
    id,
    CASE WHEN user_id = 0 THEN 'Anonymous' ELSE CONCAT('User ', user_id) END as donor,
    amount,
    recurring,
    status,
    created_at
FROM user_donations
ORDER BY id DESC
LIMIT 10;
```

### Check Active Subscriptions
```sql
SELECT 
    u.name,
    ud.amount,
    ud.subscription_id,
    ud.created_at
FROM user_donations ud
JOIN users u ON ud.user_id = u.id
WHERE ud.recurring = 1 
  AND ud.status = 'completed'
  AND ud.subscription_id IS NOT NULL;
```

### Revenue Summary
```sql
SELECT 
    COUNT(*) as total_donations,
    SUM(amount) as total_amount,
    SUM(CASE WHEN recurring = 0 THEN amount ELSE 0 END) as one_time_total,
    SUM(CASE WHEN recurring = 1 THEN amount ELSE 0 END) as recurring_total,
    COUNT(DISTINCT CASE WHEN user_id > 0 THEN user_id END) as unique_donors
FROM user_donations
WHERE status = 'completed';
```

## Troubleshooting Common Issues

### Issue: Modal doesn't open
**Check:**
```javascript
// Browser console
typeof openDonationModal === 'function'
document.getElementById('donationModal')
typeof $ !== 'undefined'
```

**Fix:** Ensure donate.blade.php is included in layout

### Issue: "Stripe is not defined"
**Check:**
```javascript
// Browser console
typeof Stripe === 'function'
```

**Fix:** Ensure Stripe.js is loaded:
```html
<script src="https://js.stripe.com/v3/"></script>
```

### Issue: Auth section not showing
**Check:**
```javascript
// In donate.blade.php
console.log('isLoggedIn:', isLoggedIn);
console.log('recurring:', recurring);
console.log('anonymous:', anonymous);
```

**Fix:** Verify checkAuthRequirements() logic

### Issue: Payment fails silently
**Check:**
- Browser network tab for 500 errors
- Laravel logs: `storage/logs/laravel.log`
- Stripe dashboard for failed PaymentIntents

**Common causes:**
- Invalid Stripe keys
- Card declined
- Network timeout
- Missing database fields

### Issue: DataTables not loading
**Check:**
```javascript
// Browser console
typeof $.fn.dataTable === 'function'
```

**Fix:** Ensure jQuery loads before DataTables

### Issue: CSV export is empty
**Check:**
- Filters are not excluding all data
- Database has completed donations
- Route is correct in view

## Performance Testing

### Load Testing Recommendations
1. **Create 1000+ test donations:**
   ```php
   // Seeder
   UserDonation::factory()->count(1000)->create();
   ```

2. **Test admin page load time** (should be < 2 seconds)

3. **Test DataTables performance** with pagination

4. **Monitor database queries:**
   ```bash
   php artisan debugbar:publish
   ```

## Security Testing

### Test Access Control
1. **Attempt to edit other user's donation** (should fail)
2. **Access admin routes without role** (should redirect)
3. **Cancel another user's subscription** (should fail)
4. **Generate receipt for anonymous donation** (should fail)

### Test Input Validation
1. **Send negative amount** (should reject)
2. **Send amount > PHP_INT_MAX** (should reject)
3. **Send 1001 char message** (should reject)
4. **Send invalid status in edit** (should reject)

### Test CSRF Protection
1. **Submit without CSRF token** (should fail)

## Stripe Dashboard Checks

After testing:
1. **Navigate to:** https://dashboard.stripe.com/test/payments
2. **Verify PaymentIntents created**
3. **Check Subscriptions tab** for recurring donations
4. **Verify amounts match database**
5. **Check customer records** created

## Checklist for Production

Before deploying to production:

- [ ] All test scenarios pass
- [ ] Stripe test keys replaced with live keys
- [ ] Database migration run on production
- [ ] Error logging configured
- [ ] Email notifications tested (if implemented)
- [ ] Tax ID added to receipt template
- [ ] Webhook endpoint configured (if implemented)
- [ ] Admin users have correct roles
- [ ] SSL certificate valid
- [ ] CSRF protection enabled
- [ ] Rate limiting configured
- [ ] Backup strategy in place

## Support Resources

**Stripe Testing:**
- Test Cards: https://stripe.com/docs/testing
- 3D Secure: https://stripe.com/docs/testing#regulatory-cards
- Webhooks: https://stripe.com/docs/webhooks/test

**Laravel:**
- Cashier Docs: https://laravel.com/docs/billing
- Validation: https://laravel.com/docs/validation

**DataTables:**
- Documentation: https://datatables.net/manual/
- Debugger: https://debug.datatables.net/

---

**Happy Testing! 🎉**

For issues, check:
1. Browser console
2. `storage/logs/laravel.log`
3. Stripe dashboard
4. Database records

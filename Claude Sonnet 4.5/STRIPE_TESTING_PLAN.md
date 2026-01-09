# Stripe Checkout Testing Plan

## Test Environment Setup

### Prerequisites
✅ Stripe test API keys configured in `.env`
✅ Database migrations run (purchases, stripe ID columns)
✅ Products synced to Stripe (via `stripe:sync-products`)
✅ Local server running: `php artisan serve`

### Test User Account
- **Email:** test@unhushed.org (or create new)
- **Role:** user
- **Stripe Customer:** Will be created on first checkout

---

## Test Scenario 1: One-Time Product Purchase (Category 3 - Books)

### Setup
1. Navigate to `http://127.0.0.1:8000/educators/store`
2. Find a book product (e.g., "Where Do Babies Come From?")
3. Verify product has available variant with price > $0

### Steps
1. **Add to Cart**
   - Click "Add to Cart" on product page
   - Verify cart shows product with correct price
   - URL: `/educators/cart_products`

2. **Initiate Checkout**
   - Click "Checkout" button
   - Ensure logged in (redirects to login if not)
   - Submit form with POST to `/educators/stripe/checkout`

3. **Expected Behavior:**
   - Redirects to Stripe Checkout page (hosted by Stripe)
   - Shows product name, price, quantity
   - Test card: `4242 4242 4242 4242` (Exp: any future date, CVC: any 3 digits)

4. **Complete Payment**
   - Enter test card details
   - Click "Pay"
   - Should redirect to `/educators/checkout/success?session_id=...`

5. **Verify Results:**
   - Success page confirms purchase
   - Database check:
     ```sql
     SELECT * FROM purchases WHERE user_id = [YOUR_USER_ID] ORDER BY created_at DESC LIMIT 1;
     ```
   - Expected fields:
     - `stripe_checkout_session_id` = session ID from URL
     - `stripe_payment_intent_id` = pi_xxx
     - `type` = 'product'
     - `status` = 'completed'
     - `amount` = product price
     - `completed_at` = timestamp
   - Cart cleared: `SELECT * FROM carts WHERE id = [CART_ID]` → status = 2 (CART_COMPLETE)
   - Session cart_id cleared

### Edge Cases to Test
- **Empty cart:** Try `/educators/stripe/checkout` directly → should redirect with error
- **Guest checkout:** Log out, add to cart → should redirect to login
- **Multiple items:** Add 2-3 different products → all should appear in Stripe Checkout

---

## Test Scenario 2: Curriculum Subscription (Category 1)

### Setup
1. Navigate to curriculum page
2. Select a curriculum product (e.g., "Elementary School Curriculum")
3. Choose quantity (e.g., 5 users for tier 3-9)

### Steps
1. **Add to Cart**
   - Add curriculum with quantity
   - Verify cart shows subscription item

2. **Initiate Checkout**
   - POST to `/educators/stripe/checkout`
   - System should detect curriculum type

3. **Expected Behavior:**
   - Redirects to Stripe Checkout in **subscription mode**
   - Shows recurring annual billing
   - Price matches `curriculum_prices` tier for quantity range
   - Test card: `4242 4242 4242 4242`

4. **Complete Payment**
   - Enter test card
   - Submit payment
   - Redirect to success page

5. **Verify Results:**
   - Database checks:
     ```sql
     -- Purchase record
     SELECT * FROM purchases WHERE type = 'subscription' ORDER BY created_at DESC LIMIT 1;
     
     -- Subscription record
     SELECT * FROM subscriptions WHERE user_id = [YOUR_USER_ID] ORDER BY created_at DESC LIMIT 1;
     
     -- Active subscription tracking
     SELECT * FROM active_subscriptions WHERE user_id = [YOUR_USER_ID] ORDER BY created_at DESC LIMIT 1;
     ```
   - Expected:
     - `purchases.type` = 'subscription'
     - `subscriptions.stripe_id` = sub_xxx
     - `subscriptions.stripe_status` = 'active'
     - `subscriptions.name` = 'curriculum'
     - `active_subscriptions.stripe_subscription_id` = sub_xxx
     - `active_subscriptions.quantity` = selected quantity
     - `active_subscriptions.status` = 'active'

6. **Verify in Stripe Dashboard**
   - Go to: https://dashboard.stripe.com/test/subscriptions
   - Find subscription by customer email
   - Check:
     - Status: Active
     - Interval: Yearly
     - Quantity: matches user selection
     - Metadata: contains product_id, user_id, etc.

### Edge Cases to Test
- **Mixed cart:** Try adding curriculum + regular product → should show error
- **Multiple curriculum items:** Add 2 different curricula → should show error (one at a time)
- **Invalid quantity:** Try quantity outside all tier ranges → should show error

---

## Test Scenario 3: Subscription Management

### Prerequisites
- Complete Test Scenario 2 (have active subscription)

### Steps

#### 3A: View Subscriptions
1. Navigate to `/educators/subscriptions`
2. **Verify Display:**
   - Lists active curriculum subscriptions
   - Shows product name, quantity, status
   - Shows next billing date
   - Displays "Cancel" and "Manage Billing" buttons

#### 3B: Access Billing Portal
1. Click "Manage Billing" button
2. **Expected Behavior:**
   - Redirects to Stripe Customer Portal
   - URL: `https://billing.stripe.com/p/session/...`
   - Portal shows:
     - Subscription details
     - Payment methods
     - Billing history
     - Update payment method option
     - Cancel subscription option

3. **Update Payment Method:**
   - Click "Update payment method"
   - Add new card: `5555 5555 5555 4444` (Mastercard test)
   - Save changes
   - Verify in Stripe dashboard: customer has 2 payment methods

4. **Return to App:**
   - Click "Return to [App Name]" in portal
   - Should redirect back to `/educators/subscriptions`

#### 3C: Cancel Subscription
1. In app, click "Cancel Subscription" button
2. Confirm cancellation
3. **Expected Behavior:**
   - POST to `/educators/subscriptions/cancel`
   - Success message: "Subscription will be canceled at the end of the current billing period"
   - Database check:
     ```sql
     SELECT * FROM subscriptions WHERE stripe_id = 'sub_xxx';
     -- ends_at should be set to end of current period
     
     SELECT * FROM active_subscriptions WHERE stripe_subscription_id = 'sub_xxx';
     -- status should be 'canceling'
     ```
   - Stripe dashboard: subscription status = "Cancels on [date]"
   - User retains access until period end

#### 3D: Resume Subscription
1. Click "Resume Subscription" button (if available)
2. **Expected Behavior:**
   - POST to `/educators/subscriptions/resume`
   - Success message: "Subscription resumed successfully"
   - Database:
     - `subscriptions.ends_at` = NULL
     - `active_subscriptions.status` = 'active'
   - Stripe dashboard: subscription status = "Active"

---

## Test Scenario 4: Training Product (Category 7)

### Purpose
Test that trainings are treated as **one-time purchases**, NOT subscriptions

### Steps
1. Add training product to cart (e.g., "Teaching Sex Ed Online")
2. Checkout via `/educators/stripe/checkout`
3. **Verify:**
   - Stripe Checkout mode = `payment` (NOT subscription)
   - Database: `purchases.type` = 'training'
   - NO subscription created in `subscriptions` table
   - Purchase record shows one-time payment

---

## Test Scenario 5: Error Handling

### 5A: Product Without Stripe Price ID
1. **Setup:** Manually set a product variant's `stripe_price_id` to NULL in database
   ```sql
   UPDATE product_vars SET stripe_price_id = NULL WHERE var_id = [VARIANT_ID];
   ```
2. Add product to cart and checkout
3. **Expected:** Error message: "Product '[name]' is not available for purchase"

### 5B: Expired/Invalid Checkout Session
1. Complete a checkout and get session_id
2. Wait 24 hours OR manually craft invalid session_id
3. Try accessing `/educators/checkout/success?session_id=cs_invalid_xxx`
4. **Expected:** Error message and redirect to cart

### 5C: Payment Failure
1. Use declined test card: `4000 0000 0000 0002`
2. Attempt checkout
3. **Expected:**
   - Stripe shows "Your card was declined"
   - User remains on Stripe Checkout page
   - Can try different card
   - No Purchase record created until successful

### 5D: Network Failure During Success Callback
1. **Simulate:** In CheckoutController@success, throw exception after retrieving session but before saving Purchase
2. **Expected:**
   - Error logged
   - User sees error message
   - No duplicate purchases created on retry (check by session_id uniqueness)

---

## Test Scenario 6: Stripe Webhook Events (Phase 3)

**Note:** Webhooks are part of Phase 3, but here's what to verify:

### Setup Webhook Endpoint
1. Use Stripe CLI: `stripe listen --forward-to localhost:8000/stripe/webhook`
2. Copy webhook secret to `.env`: `STRIPE_WEBHOOK_SECRET=whsec_...`

### Test Events
- `checkout.session.completed` - Verify Purchase created
- `customer.subscription.created` - Verify ActiveSubscription created
- `invoice.payment_succeeded` - Verify renewal tracked
- `customer.subscription.updated` - Verify quantity/price changes
- `customer.subscription.deleted` - Verify access revoked

---

## Verification Checklist

### Database Integrity
- [ ] No orphaned cart items after successful checkout
- [ ] Purchase records have all required fields populated
- [ ] Stripe IDs stored correctly (session, payment intent, subscription)
- [ ] Metadata JSON contains product snapshot
- [ ] Timestamps accurate (completed_at, created_at)

### Stripe Dashboard Checks
- [ ] Customer created with correct email
- [ ] Payment intents show "Succeeded" status
- [ ] Subscriptions show correct billing interval
- [ ] Products metadata matches database product IDs
- [ ] Prices match database amounts (cents conversion correct)

### User Experience
- [ ] Clear error messages for all failure scenarios
- [ ] Success page shows order confirmation
- [ ] Cart clears after successful checkout
- [ ] Email confirmations sent (if configured)
- [ ] Access granted immediately after purchase

### Security
- [ ] Authentication required for all checkout endpoints
- [ ] Session validation prevents unauthorized access
- [ ] CSRF protection on POST routes
- [ ] Stripe webhook signatures verified (Phase 3)

---

## Common Test Cards

```
Success:
4242 4242 4242 4242 - Visa
5555 5555 5555 4444 - Mastercard
3782 822463 10005   - American Express

Decline:
4000 0000 0000 0002 - Generic decline
4000 0000 0000 9995 - Insufficient funds
4000 0000 0000 0069 - Expired card

3D Secure:
4000 0025 0000 3155 - Requires authentication
```

**Expiration:** Any future date  
**CVC:** Any 3 digits (4 for Amex)  
**ZIP:** Any 5 digits

---

## Rollback Plan

If testing reveals critical issues:

1. **Revert to PayPal:**
   - Old routes still exist
   - Switch checkout form action back to PayPal endpoint
   - No data loss (old tables intact)

2. **Fix and Retry:**
   - Clear test data: `DELETE FROM purchases WHERE stripe_checkout_session_id LIKE 'cs_test_%';`
   - Clear test subscriptions: `DELETE FROM subscriptions WHERE stripe_id LIKE 'sub_%';`
   - Re-run sync: `php artisan stripe:sync-products --force`

3. **Stripe Dashboard Cleanup:**
   - Test mode data can be deleted anytime
   - Products → select all → delete
   - Customers → select all → delete
   - Re-sync will recreate

---

## Success Criteria

✅ All test scenarios pass without errors  
✅ Database records match Stripe dashboard  
✅ No duplicate purchases created  
✅ Cart properly cleared after checkout  
✅ Subscriptions renewable via webhooks (Phase 3)  
✅ Access granted/revoked correctly  
✅ User can manage subscriptions via portal  
✅ Error handling graceful and informative

---

## Next Steps After Testing

1. **Phase 3:** Implement webhook handlers for automated processing
2. **Phase 4:** Update frontend (cart views, checkout buttons)
3. **Phase 5:** Remove PayPal code and legacy tables
4. **Phase 6:** Production deployment with live Stripe keys
5. **Phase 7:** Monitor and optimize

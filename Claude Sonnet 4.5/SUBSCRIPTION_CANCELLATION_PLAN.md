# Subscription Cancellation & License Management Plan

## Current Schema Analysis

### Tables Involved:
1. **subscriptions** - User subscription records
   - `active` (0=inactive, 1=active)
   - `stripe_subscription_id`
   - `exp_date` - Synced with Stripe
   - `user_id`, `product_id`, `order_id`

2. **active_subscriptions** - Organization license pool
   - `org_id` - Organization owning the licenses
   - `product_id` - Which curriculum
   - `status` (0=inactive, 1=active, 3=canceled)
   - `total` - Total licenses purchased
   - `used` - Licenses currently assigned
   - `category` - Product category (1=curriculum)

3. **product_assignments** - Individual user access grants
   - `user_id` - User with access
   - `product_id` - Product they can access
   - `category` - Duplicate of product category
   - `active` (0=revoked, 1=granted)

---

## Implementation Plan

### Phase 1: Add Status Constants to Models

**Active Subscriptions Status:**
- `STATUS_INACTIVE = 0` - Not active
- `STATUS_ACTIVE = 1` - Currently active
- `STATUS_CANCELED = 3` - Canceled (per RenewalsController line 76)

**Files to Update:**
- `app/Models/ActiveSubscriptions.php` - Add status constants
- `app/Models/ProductAssignments.php` - Add active constants for clarity

---

### Phase 2: Update Subscription Cancellation Logic

**When `subscriptions.active` → 0:**

1. **Update Organization License Pool** (`active_subscriptions`)
   ```php
   ActiveSubscriptions::where('org_id', $user->org_id)
       ->where('product_id', $productId)
       ->update([
           'status' => ActiveSubscriptions::STATUS_CANCELED,
           'total' => 0,
           'used' => 0,
       ]);
   ```

2. **Revoke All User Access** (`product_assignments`)
   ```php
   ProductAssignments::where('stripe_subscription_id', $stripeSubscriptionId)
       ->update(['active' => 0]);
   ```

**Locations to Implement:**
- `app/Http/Controllers/Store/StripeWebhookController.php::handleCustomerSubscriptionDeleted()`
- `app/Http/Controllers/Store/SubscriptionController.php::cancel()`
- Any manual cancellation endpoints

---

### Phase 3: Schema Analysis & Recommendations

#### 1. `product_assignments.stripe_subscription_id`

**Current Purpose:** Links user access to the Stripe subscription that granted it

**Intended Use Cases:**
- ✅ **Bulk Revocation**: When subscription cancels, revoke ALL users under that subscription
- ✅ **Audit Trail**: Track which subscription granted access
- ✅ **Webhook Processing**: Match webhook events to affected users
- ✅ **Multi-Subscription Support**: Users could have multiple subscriptions to same product

**Recommendation:** **KEEP THIS FIELD**
- Essential for tracking subscription-granted access
- Enables bulk operations on cancellation
- Provides audit trail for compliance
- Required for webhook event processing

---

#### 2. `product_assignments.category`

**Current Purpose:** Duplicates the product category for quick filtering

**Analysis:**
- ✅ **Performance**: Avoids JOIN to products table for category filtering
- ❌ **Data Integrity**: Duplicate data can become inconsistent
- ❌ **Maintenance**: Must update in two places if product category changes
- ✅ **Query Simplicity**: `WHERE category = 1` vs `JOIN products WHERE products.category = 1`

**Current Usage Pattern:**
```php
// Common query pattern in codebase:
ProductAssignments::where('user_id', $userId)
    ->where('category', 1) // Filter curriculum
    ->get();
```

**Recommendation:** **KEEP FOR NOW, but consider migration path**

**Short-term (Current):**
- Keep `category` for performance in high-frequency queries
- Document that it must match `products.category`
- Add validation to ensure consistency

**Long-term (Future Refactor):**
- Create database view or query scope:
  ```php
  // In ProductAssignments model
  public function scopeCurriculum($query) {
      return $query->join('products', 'product_assignments.product_id', '=', 'products.id')
          ->where('products.category', 1);
  }
  ```
- Add database index on `products.category` for JOIN performance
- Migration to remove duplicate column

---

### Phase 4: Implementation Checklist

#### A. Update Models with Constants
- [ ] `ActiveSubscriptions.php` - Add `STATUS_CANCELED = 3`
- [ ] `ProductAssignments.php` - Add `ACTIVE = 1`, `INACTIVE = 0`
- [ ] `Subscriptions.php` - Add `ACTIVE = 1`, `INACTIVE = 0`

#### B. Create Cancellation Service
- [ ] Create `app/Services/SubscriptionCancellationService.php`
- [ ] Method: `cancelSubscription($stripeSubscriptionId)`
  - [ ] Set `subscriptions.active = 0`
  - [ ] Set `active_subscriptions.status = 3`
  - [ ] Set `active_subscriptions.total = 0`
  - [ ] Set `active_subscriptions.used = 0`
  - [ ] Set all `product_assignments.active = 0` for subscription
  - [ ] Log all actions
  - [ ] Return success/failure

#### C. Update Webhook Handler
- [ ] `StripeWebhookController::handleCustomerSubscriptionDeleted()`
  - [ ] Call cancellation service
  - [ ] Handle errors gracefully
  - [ ] Log webhook processing

#### D. Update Manual Cancellation
- [ ] `SubscriptionController::cancel()`
  - [ ] Call cancellation service instead of direct Stripe call
  - [ ] Update UI to reflect all changes

#### E. Testing Requirements
- [ ] Test webhook cancellation flow
- [ ] Test manual cancellation flow
- [ ] Verify organization license counts reset
- [ ] Verify all user access revoked
- [ ] Test renewal after cancellation
- [ ] Test partial cancellation (org with multiple subscriptions)

---

## Data Flow Diagram

```
SUBSCRIPTION CANCELLATION EVENT
        ↓
[Webhook: customer.subscription.deleted]
   OR [Manual: Cancel Button]
        ↓
SubscriptionCancellationService
        ↓
┌───────────────────────────────┐
│ 1. subscriptions              │
│    - active = 0               │
│    - exp_date remains         │
└───────────────────────────────┘
        ↓
┌───────────────────────────────┐
│ 2. active_subscriptions       │
│    - status = 3 (canceled)    │
│    - total = 0                │
│    - used = 0                 │
└───────────────────────────────┘
        ↓
┌───────────────────────────────┐
│ 3. product_assignments        │
│    WHERE stripe_subscription_id
│    - active = 0 (all users)   │
└───────────────────────────────┘
        ↓
[Log Event & Notify]
```

---

## Key Decisions Summary

| Field | Keep/Remove | Reasoning |
|-------|-------------|-----------|
| `product_assignments.stripe_subscription_id` | **KEEP** | Essential for bulk operations, audit trail, webhook processing |
| `product_assignments.category` | **KEEP (short-term)** | Performance optimization; plan future refactor with proper indexes |

---

## Next Steps

1. **Immediate**: Implement cancellation service with proper cascade logic
2. **Short-term**: Add validation to ensure category consistency
3. **Long-term**: Plan index-based refactor to eliminate category duplication

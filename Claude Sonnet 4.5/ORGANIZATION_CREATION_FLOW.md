# Organization Creation Flow for Subscription Purchases

## Overview
When a user purchases a subscription, the system now implements a complete digital delivery workflow that includes:
1. **Role Assignment**: Automatic assignment of "head" role
2. **Organization Checking**: Validation of existing organization membership
3. **Email Domain Matching**: Auto-assignment to organizations with matching email domains
4. **Organization Creation**: Guided creation workflow when no match is found
5. **Capacity Tracking**: License management with total/used tracking

## Complete User Flow

### Scenario 1: User Already Has Organization
```
1. User purchases subscription
2. Payment succeeds
3. System checks user.org_id > 0
4. Assigns "head" role
5. Creates subscriptions, assignments, and active_subscriptions
6. Redirects to /subscribed
```

### Scenario 2: Email Domain Matches Existing Organization
```
1. User purchases subscription (user.org_id = 0)
2. Payment succeeds
3. System extracts email domain (e.g., john@schooldistrict.edu → schooldistrict.edu)
4. Finds organization with email_match = "schooldistrict.edu"
5. Auto-assigns user to that organization
6. Assigns "head" role
7. Creates subscriptions, assignments, and active_subscriptions
8. Redirects to /subscribed
```

### Scenario 3: No Organization Match - Creation Required
```
1. User purchases subscription (user.org_id = 0)
2. Payment succeeds
3. System extracts email domain (no match found)
4. Stores pending subscription data in session
5. Redirects to /{path}/subscription/create-organization
6. User fills out organization form (name, address, email_match, etc.)
7. System creates organization
8. Assigns org_id to user
9. Assigns "head" role
10. Completes subscription processing (subscriptions, assignments, capacity)
11. Clears pending session data
12. Redirects to /subscription/success
```

## Database Changes

### active_subscriptions Table
Now properly tracks capacity:
- `total`: Total licenses purchased (quantity)
- `used`: Number of users currently assigned
- `status`: 1=active, 0=inactive
- Logic: When purchasing additional licenses, `total` is increased

### Session Variables (Temporary)
During organization creation flow:
```php
session([
    'pending_subscription_completion' => [
        'order_id' => 123,
        'subscription_id' => 'sub_xxx',
        'user_id' => 456
    ],
    'email_domain' => 'schooldistrict.edu',
    'user_name' => 'John Doe',
    'has_pending_subscription' => true
]);
```

## File Changes

### Routes (routes/web.php)
```php
Route::get('/subscription/create-organization', [CartSubscriptController::class, 'showCreateOrganization']);
Route::post('/subscription/create-organization', [CartSubscriptController::class, 'storeOrganization']);
```

### Controller Methods (CartSubscriptController.php)

#### processSubscriptionCompletion($order, $subscription, $user, $path)
- **Line 491-586**: Main fulfillment method
- **Returns**: `['needs_org_creation' => true]` or `null` (success)
- **Actions**:
  1. Mark order complete
  2. Assign "head" role
  3. Check organization (calls ensureUserHasOrganization)
  4. If needs org: store session data and return status
  5. Loop through subscription items
  6. Create Subscriptions records
  7. Create ProductAssignments
  8. Call manageActiveSubscriptionCapacity

#### ensureUserHasOrganization($user)
- **Line 590-626**: Organization validation logic
- **Returns**: Array with status
- **Logic**:
  - Check if org_id > 0 → return success
  - Extract email domain
  - Search for organizations.email_match
  - If found: auto-assign and return success
  - If not found: return `['needs_org_creation' => true, 'email_domain' => $domain]`

#### manageActiveSubscriptionCapacity($orgId, $productId, $qtyPurchased)
- **Line 640-692**: Capacity tracking logic
- **Logic**:
  - Find existing ActiveSubscription
  - If exists: increase `total` by `qtyPurchased`, set status=1
  - If new: create with `total = qtyPurchased`, `used = 0`
  - If `used > total`: log warning and store in session for UI display

#### showCreateOrganization($path)
- **Line 1115-1138**: Display organization creation form
- **Checks**: User authenticated, doesn't already have org
- **View**: backend.users.org-add-by-user

#### storeOrganization(Request $req, $path)
- **Line 1142-1239**: Process organization creation
- **Validation**:
  - Required: name (min 3 chars)
  - Blocked email domains: gmail.com, yahoo.com, hotmail.com, etc.
  - Duplicate email_match prevention
- **Success Flow**:
  - Create organization
  - Assign org_id to user
  - If pending subscription: retrieve Stripe subscription and call processSubscriptionCompletion
  - Clear session data
  - Redirect to success

### View (org-add-by-user.blade.php)
- **Location**: resources/views/backend/users/
- **Form Action**: /{path}/subscription/create-organization
- **Fields**:
  - Organization Name (required)
  - Pedagogy, Grades, Student Count
  - Country, Address, City, State/Province, ZIP
  - Website URL
  - Email Domain (email_match) with validation
- **JavaScript**:
  - Country selector (US → State dropdown, Other → Province textbox)
  - Email domain validation (blocks public domains)
  - Submit button loading state

### Frontend JavaScript (checkout_subscriptions.blade.php)
```javascript
// Handle org creation redirect
if(data.redirect_to_org_creation) {
    window.location.href = data.success_url; // Goes to create-organization page
}
```

## API Response Flow

### subscription_payment_process Response
```json
// Success - No org needed
{
    "success": true,
    "requires_action": false,
    "success_url": "https://.../subscribed?order_id=123"
}

// Success - Org creation needed
{
    "success": true,
    "requires_action": false,
    "redirect_to_org_creation": true,
    "success_url": "https://.../subscription/create-organization"
}

// Requires 3D Secure
{
    "success": true,
    "requires_action": true,
    "client_secret": "pi_xxx_secret_yyy",
    "success_url": "https://.../subscribed?order_id=123"
}
```

## Email Domain Matching

### Purpose
Auto-assign users to existing organizations based on their email domain.

### Example
```
Organization: Lincoln High School
email_match: lincolnhs.edu

User registers: teacher@lincolnhs.edu
→ Auto-assigned to Lincoln High School

User registers: john@gmail.com
→ No match, must create own organization
```

### Blocked Domains
Public email providers are blocked to prevent incorrect auto-assignment:
- gmail.com
- yahoo.com / yahoo.co.uk
- hotmail.com / outlook.com
- aol.com
- icloud.com
- protonmail.com
- mail.com

## Capacity Overflow Detection

### Scenario
```
1. Organization purchases 50 licenses (total = 50)
2. Admin assigns 50 users (used = 50)
3. Organization purchases 25 more licenses (total = 75)
4. System recalculates: used=50, total=75 → OK

Alternative:
1. Organization purchases 100 licenses (total = 100)
2. Admin assigns 100 users (used = 100)
3. 20 licenses expire/cancelled (total = 80)
4. System detects: used=100, total=80 → OVERFLOW
5. Session warning stored for admin to unassign 20 users
```

### Warning Storage
```php
session([
    'capacity_warning' => [
        'product_id' => 123,
        'total' => 80,
        'used' => 100,
        'excess' => 20,
        'message' => "You have 20 more users assigned than your total capacity..."
    ]
]);
```

## Testing Checklist

### Test Case 1: Existing Organization
- [ ] User with org_id > 0 purchases subscription
- [ ] Payment succeeds
- [ ] "head" role assigned
- [ ] Subscription, assignment, and capacity records created
- [ ] Redirects to /subscribed

### Test Case 2: Email Domain Match
- [ ] Create org with email_match = "testschool.edu"
- [ ] New user registers with email ending @testschool.edu
- [ ] User has org_id = 0 before purchase
- [ ] Purchase subscription
- [ ] User auto-assigned to organization
- [ ] "head" role assigned
- [ ] Completes normally

### Test Case 3: Organization Creation
- [ ] User with @gmail.com purchases subscription
- [ ] Payment succeeds
- [ ] Redirected to /subscription/create-organization
- [ ] Form pre-fills with user name
- [ ] Submit form (try blocked domain → rejected)
- [ ] Submit with valid data
- [ ] Organization created
- [ ] org_id assigned to user
- [ ] Subscription completed
- [ ] Redirects to /subscription/success

### Test Case 4: Capacity Tracking
- [ ] Purchase subscription with quantity=10
- [ ] Check active_subscriptions: total=10, used=0
- [ ] Purchase additional quantity=5
- [ ] Check active_subscriptions: total=15, used=0
- [ ] Manually set used=20 (overflow scenario)
- [ ] Purchase quantity=5
- [ ] Check session for capacity_warning

### Test Case 5: Validation
- [ ] Try creating org with name < 3 chars → rejected
- [ ] Try email_match = "gmail.com" → rejected
- [ ] Try duplicate email_match → rejected
- [ ] Try creating org when already have org_id → redirected

## Future Enhancements

1. **User Reassignment Workflow**
   - When capacity_warning exists, show UI for bulk unassignment
   - Admin can select users to remove from product access
   - Decreases `used` count to match `total`

2. **Organization Approval Workflow**
   - Flag organizations for admin review
   - Prevent auto-assignment until approved
   - Email notifications for approval requests

3. **Bulk Organization Import**
   - CSV upload for schools/districts
   - Pre-populate email_match domains
   - Prevent duplicate domain conflicts

4. **Organization Dashboard**
   - View total/used capacity per product
   - Manage user assignments
   - Purchase additional licenses
   - View expiration dates

## Troubleshooting

### User Stuck on Org Creation Page
**Symptom**: Form submitted but subscription not completing
**Check**:
1. Session data: `session('pending_subscription_completion')`
2. Stripe subscription ID valid
3. Controller logs for exceptions
**Fix**: Clear session and manually call processSubscriptionCompletion

### Duplicate Organizations Created
**Symptom**: Same email domain in multiple orgs
**Check**: Organizations.email_match column
**Fix**: Update duplicate validation in storeOrganization method

### Capacity Not Tracking
**Symptom**: active_subscriptions.total not updating
**Check**:
1. OrderItems.qty value
2. manageActiveSubscriptionCapacity logs
3. Database total/used columns
**Fix**: Ensure OrderItems.item_type = ITEM_TYPE_SUBSCRIPTION

### Email Domain Not Matching
**Symptom**: User with matching domain not auto-assigned
**Check**:
1. extractEmailDomain() output
2. Organizations.email_match exact string
3. Case sensitivity (should be case-insensitive)
**Fix**: Verify email_match is lowercase in database

# Phase 2A: USPS Integration - Completion Summary

## ✅ Completed Tasks

### 1. Configuration Files
**File:** `config/usps.php`
- Added USPS Web Tools API configuration
- Configured test and production endpoints
- Set weight limits (70 lbs domestic, 66 lbs international)
- Added default shipping options
- Origin zip code configuration

### 2. USPS Shipping Service
**File:** `app/Services/USPSShippingService.php`
- Full USPS Web Tools API integration (Version 1.21 - 2025 Updates)
- **Domestic Rate Calculation** (`getDomesticRates`)
  - RateV4 API implementation
  - Priority, Express, First-Class support
  - Delivery time estimation
- **International Rate Calculation** (`getInternationalRates`)
  - IntlRateV2 API implementation
  - Country-specific rates
  - Value declaration for customs
- **Address Validation** (`validateAddress`)
  - USPS Verify API integration
  - Address standardization
  - API field storage (api_address1, api_city, api_state, api_zip5)
- **Weight Calculation** (`calculateCartWeight`)
  - Automatic weight totaling from cart items
  - Pounds/ounces conversion
  - Digital product exclusion
- **Helper Methods**
  - XML request builders
  - Response parsers
  - Service name formatting
  - Delivery day estimation
  - Digital-only cart detection

### 3. Controller Updates
**File:** `app/Http/Controllers/Store/CartProductController.php`
- **Updated `getShippingRates()` method**
  - Uses new USPSShippingService
  - Digital-only cart detection
  - Domestic vs international routing
  - Error handling and logging
- **Added `validateAddressUSPS()` method**
  - USPS address validation
  - Correction detection
  - API field updates

### 4. Routes
**File:** `routes/web.php`
- Shipping rates endpoint already exists: `GET /shipping/rates`
- Address validation endpoint exists: `POST /address/validate`

## 📋 Required Environment Variables

Add to your `.env` file:

```env
# USPS Web Tools API
USPS_USERNAME=your_usps_username
USPS_PASSWORD=your_usps_password
USPS_MODE=test  # or 'production'

# Shipping Origin
USPS_ORIGIN_ZIP=07304  # Your warehouse zip code
USPS_ORIGIN_ZIP4=  # Optional ZIP+4
```

## 🔑 USPS API Setup Instructions

1. **Register for USPS Web Tools**
   - Visit: https://www.usps.com/business/web-tools-apis/
   - Sign up for a Web Tools account
   - Request API access for:
     - RateV4 (Domestic Rates)
     - IntlRateV2 (International Rates)
     - Verify (Address Validation)

2. **Get Your Credentials**
   - You'll receive a Username via email
   - Use this username in your `.env` file
   - Test mode uses the same credentials

3. **Test Your Integration**
   - Start with `USPS_MODE=test`
   - USPS test server: `https://secure.shippingapis.com/ShippingAPITest.dll`
   - Production server: `https://secure.shippingapis.com/ShippingAPI.dll`

## 🎯 Next Steps (Phase 2B)

1. **Update Address Blade View**
   - Add shipping rates display section
   - Show delivery estimates
   - Add shipping method selection
   - Display weight limit warnings

2. **Update Cart Flow**
   - Fetch rates after address validation
   - Store selected shipping method in session
   - Pass shipping cost to Stripe checkout

3. **Test With Real Data**
   - Test domestic shipping rates
   - Test international shipping rates
   - Test weight limit handling
   - Test address validation corrections

4. **Error Handling**
   - Add user-friendly error messages
   - Handle USPS API timeouts
   - Cache rates for performance

## 📊 API Endpoints Available

### Get Shipping Rates
```
GET /{path}/shipping/rates?address_id={id}
```
Response:
```json
{
  "success": true,
  "rates": [
    {
      "service": "Priority Mail",
      "rate": 8.95,
      "delivery_days": "1-3 business days",
      "display_name": "USPS Priority Mail®"
    }
  ]
}
```

### Validate Address
```
POST /{path}/address/validate
Body: { "address_id": 123 }
```
Response:
```json
{
  "success": true,
  "validated": true,
  "corrected": true,
  "original": {
    "street": "123 Main St",
    "city": "New York",
    "state": "NY",
    "zip": "10001"
  },
  "validated_address": {
    "street": "123 MAIN ST",
    "city": "NEW YORK",
    "state": "NY",
    "zip": "10001"
  }
}
```

## 🧪 Testing Checklist

- [ ] Test domestic rates with valid US address
- [ ] Test international rates with various countries
- [ ] Test weight limits (over 70 lbs domestic, 66 lbs international)
- [ ] Test address validation with correctable address
- [ ] Test address validation with invalid address
- [ ] Test digital-only cart (should skip shipping)
- [ ] Test mixed cart (digital + physical)
- [ ] Test API error handling
- [ ] Test with USPS test credentials
- [ ] Verify logging for debugging

## 🔧 Configuration Tips

### Weight Configuration
Products must have `weight` field populated in `product_vars` table (in ounces)

### Ship Type Values
- `digital` = Digital download (no shipping)
- `media` = Media mail eligible
- `standard` = Standard package

### Origin Zip Code
Set `USPS_ORIGIN_ZIP` to your warehouse/fulfillment center location

## 📝 Notes

- USPS API responses are XML (automatically parsed to arrays)
- Rate requests are subject to USPS API rate limits
- Consider caching rates for same address/weight combinations
- USPS test mode uses same API endpoints with test credentials
- International shipping requires value declaration for customs
- Address validation only works for US addresses

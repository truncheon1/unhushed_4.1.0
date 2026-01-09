<?php

return [
    /*
    |--------------------------------------------------------------------------
    | USPS API Configuration (OAuth 2.0)
    |--------------------------------------------------------------------------
    |
    | New USPS API using OAuth 2.0 authentication
    | Documentation: https://developers.usps.com/
    |
    */

    // OAuth 2.0 Credentials
    'client_id' => env('USPS_CLIENT_ID', ''),
    'client_secret' => env('USPS_CLIENT_SECRET', ''),
    'customer_registration_id' => env('USPS_CUSTOMER_REGISTRATION_ID', ''),
    'mailer_id' => env('USPS_MAILER_ID', ''),
    
    // API Endpoints (New REST API)
    'endpoints' => [
        'production' => [
            'oauth' => 'https://apis.usps.com/oauth2/v3/token',
            'base' => 'https://api.usps.com',
        ],
        'test' => [
            'oauth' => 'https://apis.usps.com/oauth2/v3/token',
            'base' => 'https://api-cat.usps.com',
        ],
    ],
    
    'mode' => env('USPS_MODE', 'test'), // 'test' or 'production'
    
    // OAuth token cache settings
    'token_cache_key' => 'usps_oauth_token',
    'token_cache_ttl' => 3600, // 1 hour (tokens expire after ~4 hours)
    
    // Default shipping options
    'defaults' => [
        'container' => 'VARIABLE',
        'size' => 'REGULAR',
        'machinable' => true,
    ],
    
    // Weight limits (in pounds)
    'weight_limits' => [
        'domestic' => 70,
        'international' => 66,
    ],
    
    // Origin address (your warehouse/shipping location)
    'origin' => [
        'zip' => env('USPS_ORIGIN_ZIP', ''),
        'zip4' => env('USPS_ORIGIN_ZIP4', ''),
        'street' => env('USPS_ORIGIN_STREET', ''),
        'city' => env('USPS_ORIGIN_CITY', ''),
        'state' => env('USPS_ORIGIN_STATE', ''),
    ],

    // Addresses 3.0 validation path (configurable for quick tuning)
    'address_validate_path' => env('USPS_ADDRESS_VALIDATE_PATH', '/addresses/v3/address'),
];

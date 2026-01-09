<?php

use App\Models\User;

return [
    'auth_url'          => 'https://appcenter.intuit.com/connect/oauth2',
    'base_url'          => env('QUICKBOOKS_MODE', config('app.env') === 'production' ? 'Production' : 'Development'),
    'live_client_id'    => env('QUICKBOOKS_CLIENT_ID'),
    'live_secret'       => env('QUICKBOOKS_CLIENT_SECRET'),
    'live_redirect'     => env('QUICKBOOKS_REDIRECT_URI'),
    'sandbox_id'        => env('QUICKBOOKS_SANDBOX_CLIENT_ID'),
    'sandbox_secret'    => env('QUICKBOOKS_SANDBOX_CLIENT_SECRET'),
    'sandbox_redirect'  => env('QUICKBOOKS_SANDBOX_REDIRECT_URI'),
    'scope'             => 'com.intuit.quickbooks.accounting, com.intuit.quickbooks.payment, openid, profile, email, phone, address',
    'tokenEndPointUrl'  => 'https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer',

    'access_token'      => env('QUICKBOOKS_ACCESS_TOKEN'),
    'refresh_token'     => env('QUICKBOOKS_REFRESH_TOKEN'),
    'realm_id'          => env('QUICKBOOKS_REALM_ID'),


    'logging' => [
        'enabled' => env('QUICKBOOKS_DEBUG', config('app.debug')),
        'location' => storage_path('logs'),
    ],

    'route' => [
        // Controls the middlewares for thr routes.  Can be a string or array of strings
        'middleware' => [
            // Added to the protected routes for the package (i.e. connect & disconnect)
            'authenticated' => 'auth',
            // Added to all of the routes for the package
            'default' => 'web',
        ],
        'paths' => [
            // Show forms to connect/disconnect
            'connect' => 'connect',
            // The DELETE takes place to remove token
            'disconnect' => 'disconnect',
            // Return URI that QuickBooks sends code to allow getting OAuth token
            'token' => 'token',
        ],
        'prefix' => 'quickbooks',
    ],

    'user' => [
        'keys' => [
            'foreign' => 'user_id',
            'owner' => 'id',
        ],
        'model' => User::class,
    ],

];
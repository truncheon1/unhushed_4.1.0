<?php

return [
    
    // Active Campaign API Configuration
    'url' => env('ACTIVECAMPAIGN_URL', 'https://unhushed.api-us1.com'),
    'key' => env('ACTIVECAMPAIGN_API_KEY'),

    'timeout' => 100,

    'retry_times' => 3,

    'retry_sleep' => 5,

    /**
     * Custom Field ID Mappings
     * Maps friendly names to Active Campaign field IDs
     */
    'custom_fields' => [
        'user_id'               => 1,      // Internal UNHUSHED user ID
        'org_id'                => 2,      // Organization ID
        'age_work_with'         => 3,      // Age group worked with
        'address'               => 4,      // Street address
        'birthday'              => 5,      // Date of birth
        'last_login'            => 10,     // Last login date (YYYY-MM-DD)
        'user_count_es'         => 16,     // Elementary school user count
        'user_count_ms'         => 17,     // Middle school user count
        'user_count_hs'         => 18,     // High school user count
        'message'               => 19,     // Custom message field
        'last_meeting'          => 21,     // Last meeting date
        'children'              => 22,     // Children info
        'bot_filter'            => 23,     // Bot filter flag
        'fundraiser_year'       => 24,     // Fundraiser year
        'fundraiser_total'      => 25,     // Fundraiser total raised
        'tracking'              => 26,     // Tracking number (shipping)
    ],

    /**
     * List ID Mappings
     * Maps friendly names to Active Campaign list IDs
     */
    'lists' => [
        'newsletter'            => 1,      // Newsletter subscribers
        'site_user'             => 2,      // Site registered users
    ],

    /**
     * Tag ID Mappings
     * Maps friendly names to Active Campaign tag IDs for automations
     */
    'tags' => [
        'abandoned_cart'        => 140,    // Abandoned cart automation
        'cart_shipped'          => 229,    // Order shipped automation
        'english'               => 1,      // English language tag
    ],

];

<?php
return [
    'env'      => env('BUZZLEAD_ENV', 'sandbox'),
    'urls'     => [
        'sandbox'    => env('BUZZLEAD_URL_SANDBOX', 'http://test.buzzlead.com.br'),
        'production' => env('BUZZLEAD_URL_PRODUCTION', 'https://app.buzzlead.com.br'),
    ],
    'script' => [
        'sandbox'    => env('BUZZLEAD_SCRIPT_SANDBOX', 'http://test.buzzlead.com.br/tracker.js'),
        'production' => env('BUZZLEAD_SCRIPT_PRODUCTION', 'https://app.buzzlead.com.br/tracker.js'),
    ],
    'api'      => [
        'email' => env('BUZZLEAD_API_EMAIL', ''),
        'token' => env('BUZZLEAD_API_TOKEN', ''),
        'key'   => env('BUZZLEAD_API_KEY', '')
    ],
    // Main campaign
    'campaign' => env('BUZZLEAD_MAIN_CAMPAIGN', '')
];
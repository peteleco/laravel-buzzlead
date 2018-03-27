<?php
return [
    'env'              => env('BUZZLEAD_ENV', 'sandbox'),
    'urls'             => [
        'sandbox'    => env('BUZZLEAD_URL_SANDBOX', 'http://test.buzzlead.com.br'),
        'production' => env('BUZZLEAD_URL_PRODUCTION', 'https://app.buzzlead.com.br'),
    ],
    'script'           => [
        'sandbox'    => env('BUZZLEAD_SCRIPT_SANDBOX', 'http://test.buzzlead.com.br/tracker.js'),
        'production' => env('BUZZLEAD_SCRIPT_PRODUCTION', 'https://app.buzzlead.com.br/tracker.js'),
    ],
    'api'              => [
        'email' => env('BUZZLEAD_API_EMAIL', ''),
        'token' => env('BUZZLEAD_API_TOKEN', ''),
        'key'   => env('BUZZLEAD_API_KEY', '')
    ],
    // Main campaign
    'campaign'         => env('BUZZLEAD_MAIN_CAMPAIGN', ''),
    'cookie'           => [
        'original' => env('BUZZLEAD_COOKIE_ORIGINAL', 'buzzlead'),
        'updated'  => env('BUZZLEAD_COOKIE_UPDATED', 'buzzlead_affiliate'),
        'expiry'   => env('BUZZLEAD_COOKIE_EXPIRY', 10080),
    ],
    // Quantos dias vai levar para ser processado
    'days_to_proccess' => env('BUZZLEAD_DAYS_TO_PROCCESS', 10),
];
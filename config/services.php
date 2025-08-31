<?php

return [
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'zimra' => [
        'base_url' => env('ZIMRA_BASE_URL', 'https://fdmsapi.zimra.co.zw'),
        'test_url' => env('ZIMRA_TEST_URL', 'https://fdmsapitest.zimra.co.zw'),
        'api_key' => env('ZIMRA_API_KEY'),
        'timeout' => env('ZIMRA_TIMEOUT', 30),
        'device_model_name' => env('ZIMRA_DEVICE_MODEL', 'ERP_POS'),
        'device_version' => env('ZIMRA_DEVICE_VERSION', '1.0'),
        'environment' => env('ZIMRA_ENVIRONMENT', 'test'), // test or production
        'device_operating_mode' => env('ZIMRA_OPERATING_MODE', 'Online'), // Online or Offline
        'qr_url' => env('ZIMRA_QR_URL', 'https://invoice.zimra.co.zw/'),
        'max_file_size' => 3145728, // 3MB in bytes
    ],
];
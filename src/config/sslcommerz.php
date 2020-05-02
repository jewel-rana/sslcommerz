<?php
// SSLCommerz configuration
return [
    'sandbox_mode' => env("SSL_SANDBOX_MODE", FALSE),
    'store_id' => env("SSL_STORE_ID"),
    'store_password' => env("SSL_STORE_PASSWORD"),
    'default_currency' => env("SSL_STORE_CURRENCY"),
    'apis' => [
        'make_payment' => "/gwprocess/v4/api.php",
        'transaction_validate' => "/validator/api/merchantTransIDvalidationAPI.php",
        'order_validate' => "/validator/api/validationserverAPI.php",
        'refund_payment' => "/validator/api/merchantTransIDvalidationAPI.php",
        'refund_status' => "/validator/api/merchantTransIDvalidationAPI.php",
    ],
    'allow_localhost' => env("SSL_LOCALHOST", FALSE),
    'success_url' => env("SSL_SUCCESS_URL", '/sslcommerz/success'),
    'fail_url' => env("SSL_FAIL_URL", '/sslcommerz/fail'),
    'cancel_url' => env("SSL_CANCEL_URL", '/sslcommerz/cancel'),
    'ipn_url' => env("SSL_IPN_URL", '/sslcommerz/ipn'),
];
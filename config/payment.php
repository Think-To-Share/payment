<?php

return [
    'default' => env('DEFAULT_PAYMENT_GATEWAY','sabpaisa'),

    'gateways' => [
        'sabpaisa' => [
            'auth_key' => env('SABPAISA_AUTH_KEY'),
            'auth_iv' => env('SABPAISA_AUTH_IV'),
            'client_code' => env('SABPAISA_CLIENT_CODE'),
            'username' => env('SABPAISA_USERNAME'),
            'password' => env('SABPAISA_PASSWORD'),
            'callbackUrl' => '/payment-gateway/response?gateway=sabpaisa',
        ],
        'ccavenue' => [
            'merchant_id' => env('CC_AVENUE_MERCHANT_ID'),
            'working_key' => env('CC_AVENUE_WORKING_KEY'),
            'access_code' => env('CC_AVENUE_ACCESS_CODE'),
            'language' => env('CC_AVENUE_LANGUAGE','INR'),
            'currency' => env('CC_AVENUE_CURRENCY','EN'),
            'redirect_url' => '/payment-gateway/response?gateway=ccavenue',
            'cancel_url' => '/payment-gateway/response',
        ],
        'cashfree' => [
            'client_id' => env('CASHFREE_API_KEY'),
            'client_secret_key' => env('CASHFREE_API_SECRET'),
            'currency' => env('CASHFREE_CURRENCY','INR'),
            'return_url' => '/payment-gateway/response/cashfree'
        ],
    ],

    'sandbox' => env('PAYMENT_SANDBOX_URL',false),
];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'weixin' => [
        'client_id' => env('WE_CHAT_APP_ID'),
        'client_secret' => env('WE_CHAT_APP_SECRET'),
        'redirect' => env('WE_CHAT_REDIRECT_URI'),
        'auth_base_uri' => 'https://open.weixin.qq.com/connect/qrconnect',
    ],
    'apple' => [
        'client_id' => env('APPLE_CLIENT_ID'), // client id 
        'client_secret' => env('APPLE_CLIENT_SECRET'),  // 生成好的 client_secret
        'redirect' => env('APPLE_REDIRECT_URI', '') // APP端不需要这个 为空或者随便写入一个地址 如: `https://example-app.com/redirect`
    ]
];
